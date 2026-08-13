<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        do {
            $referralCode = 'BG'.strtoupper(Str::random(7));
        } while (User::where('referral_code', $referralCode)->exists());

        $user = User::create([
            'role_id' => $role?->id,
            'sponsor_id' => $sponsor?->id,
            'referral_code' => $referralCode,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => '+91',
            'matched_bv' => 0,
            'status' => 'inactive',
            'current_rank_no' => 1,
            'current_rank_name' => 'Bronze',
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

        session()->regenerate();
        session(['auth.user_id' => $user->id, 'auth.role' => $role?->slug]);

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
