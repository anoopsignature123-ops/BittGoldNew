<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function showLogin()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identity' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->identity)
                    ->orWhere('mobile', $request->identity)
                    ->orWhere('referral_code', $request->identity);
            })
            ->whereHas('role', function ($query) {
                $query->where('slug', 'user');
            })
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['identity' => 'Invalid login credentials'])->withInput();
        }

        session()->regenerate();
        session(['auth.user_id' => $user->id, 'auth.role' => $user->role?->slug]);

        return redirect()->route('user.dashboard');
    }

    public function showRegister()
    {
        return view('user.auth.register');
    }

    public function showForgotPassword()
    {
        return view('user.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->whereHas('role', fn($query) => $query->where('slug', 'user'))->first();
        if (!$user)
            return back()->with('error', 'No member account was found with this email address.');

        // Always issue a fresh token: this makes the reset action predictable for
        // members and avoids a stale 60-second link while they are testing it.
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(['email' => $email], ['token' => Hash::make($token), 'created_at' => now()]);

        try {
            $sent = send_template_email('user-password-reset', $user->email, [
                'name' => $user->name,
                'email' => $user->email,
                'reset_link' => route('user.password.reset', ['token' => $token, 'email' => $user->email]),
                'expiry_minutes' => 60,
                'logo' => url('assets/images/logo/logofooter.png'),
                'site_name' => config('app.name', 'BittGold'),
                'site_url' => config('app.url'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
            ]);
            if (!$sent) {
                DB::table('password_reset_tokens')->where('email', $email)->delete();
                return back()->with('error', 'We could not send the reset email right now. Please try again shortly.');
            }
        } catch (\Throwable $exception) {
            Log::error('Password reset email could not be sent.', ['user_id' => $user->id, 'exception' => $exception->getMessage()]);
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->with('error', 'We could not send the reset email right now. Please try again shortly.');
        }
        return back()->with('success', 'Password reset link sent. Please check your inbox.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('user.auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['token' => ['required', 'string'], 'email' => ['required', 'email'], 'password' => ['required', 'string', 'min:8', 'confirmed']]);
        $email = strtolower(trim($request->email));
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        $isValid = $record && $record->created_at && now()->diffInMinutes($record->created_at) <= 60 && Hash::check($request->token, $record->token);
        if (!$isValid)
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired.'])->withInput();

        $user = User::where('email', $email)->whereHas('role', fn($query) => $query->where('slug', 'user'))->first();
        if (!$user)
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired.']);

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Your new password must be different from your current password.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password), 'plain_password' => $request->password]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        return redirect()->route('user.login')->with('status', 'Password updated successfully. You can now sign in.');
    }

    public function sponsorLookup(Request $request)
    {
        $code = trim((string) $request->query('referral_code'));

        if ($code === '') {
            return response()->json(['found' => false]);
        }

        $sponsor = User::where('referral_code', $code)->first(['name', 'email', 'referral_code']);

        if (! $sponsor) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'name' => $sponsor->name,
            'email' => $sponsor->email,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'string', 'max:30', 'unique:users,mobile'],
            'referral_code' => ['required', 'string', 'max:100', 'exists:users,referral_code'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $role = Role::where('slug', 'user')->first();
        $sponsor = null;

        if ($request->referral_code) {
            $sponsor = User::where('referral_code', $request->referral_code)->first();
        }

        $referralCode = $this->generateReferralCode();

        $user = User::create([
            'role_id' => $role?->id,
            'sponsor_id' => $sponsor?->id,
            'referral_code' => $referralCode,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => '+91',
            'status' => 'inactive',
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'email_verified_at' => now(),
        ]);

        UserReferral::create([
            'user_id' => $user->id,
            'sponsor_id' => $sponsor?->id,
            'sponsor_referral_code' => $sponsor?->referral_code,
            'position' => 'left',
        ]);

        try {
            send_template_email('welcome-user', $user->email, [
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->country_code . ' ' . $user->mobile,
                'userId' => $user->referral_code,
                'plain_password' => $request->password,

                'activation_link' => route('user.login'),
                'referrByName' => $user->sponsor?->name ?? 'N/A',
                'referrById' => $user->sponsor?->referral_code ?? 'N/A',
                'referrByEmail' => $user->sponsor?->email ?? 'N/A',

                'logo' => url('assets/images/logo/logofooter.png'),
                'site_name' => config('app.name', 'BittGold'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Welcome email could not be sent.', ['user_id' => $user->id, 'exception' => $exception->getMessage()]);
        }
        return redirect()->route('user.registration.success')->with('registration_details', [
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->country_code . ' ' . $user->mobile,
            'referral_code' => $user->referral_code,
            'plain_password' => $request->password,
            'sponsor_name' => $user->sponsor?->name ?? 'N/A',
            'sponsor_email' => $user->sponsor?->email ?? 'N/A',
            'sponsor_referral_code' => $user->sponsor?->referral_code ?? 'N/A',
        ]);
    }

    private function generateReferralCode(): string
    {
        do {
            $randomNumbers = mt_rand(1000000, 9999999);

            $code = 'BG' . $randomNumbers;
        } while (
            User::query()
                ->where('referral_code', $code)
                ->exists()
        );

        return $code;
    }

    public function registrationSuccess()
    {
        $details = session('registration_details');
        if (!$details)
            return redirect()->route('user.register');

        return view('user.auth.registration-success', compact('details'));
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}