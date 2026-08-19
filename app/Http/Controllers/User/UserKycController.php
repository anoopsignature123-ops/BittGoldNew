<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserKycController extends Controller
{
    public function index()
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $kyc = $user->kyc()->first();

        return view('user.kyc.index', compact('user', 'kyc'));
    }

    public function store(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $request->validate([
            'pan_number' => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'aadhaar_number' => ['required', 'digits:12'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:30'],
            'ifsc_code' => ['required', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'],
            'branch_name' => ['required', 'string', 'max:255'],
            'pan_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'aadhaar_front_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'aadhaar_back_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bank_proof_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'pan_number.regex' => 'PAN number must be in valid format like ABCDE1234F.',
            'aadhaar_number.digits' => 'Aadhaar number must be exactly 12 digits.',
            'ifsc_code.regex' => 'IFSC code must be valid format like ABCD0123456.',
        ]);

        $data = [
            'user_id' => $user->id,
            'pan_number' => strtoupper(trim($request->pan_number)),
            'aadhaar_number' => trim($request->aadhaar_number),
            'bank_name' => trim($request->bank_name),
            'account_holder_name' => trim($request->account_holder_name),
            'account_number' => trim($request->account_number),
            'ifsc_code' => strtoupper(trim($request->ifsc_code)),
            'branch_name' => trim($request->branch_name),
            'status' => 'pending',
            'rejection_reason' => null,
        ];

        $files = ['pan_photo', 'aadhaar_front_photo', 'aadhaar_back_photo', 'bank_proof_photo'];

        foreach ($files as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('kyc', 'public');
                $data[$field] = $path;
            }
        }

        $kyc = Kyc::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        $adminEmails = User::whereHas('role', fn ($query) => $query->where('slug', 'admin'))
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        if (empty($adminEmails)) {
            $adminEmails = [config('mail.from.address', 'admin@bittgold.com')];
        }

        send_template_email('kyc-submitted-admin', $adminEmails, [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'member_id' => $user->unique_id ?? $user->referral_code ?? 'N/A',
            'referral_code' => $user->referral_code ?? $user->unique_id ?? 'N/A',
            'site_name' => config('app.name'),
            'support_email' => config('mail.from.address', 'support@bittgold.com'),
            'logo' => asset('siteadmin/images/logo.png'),
        ]);

        return redirect()->route('user.kyc.index')->with('success', 'KYC details submitted successfully. Admin will review them soon.');
    }
}
