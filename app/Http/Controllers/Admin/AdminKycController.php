<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\User;
use Illuminate\Http\Request;

class AdminKycController extends Controller
{
    protected function authorizeAdmin()
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'admin') {
            return redirect()->route('admin.login');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        $kycs = Kyc::with('user')->latest()->get();

        return view('admin.kycs.index', compact('kycs'));
    }

    public function approve(Kyc $kyc)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        $kyc->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'approved_at' => now(),
        ]);

        if ($kyc->user && $kyc->user->email) {
            send_template_email('kyc-status-user', $kyc->user->email, [
                'user_name' => $kyc->user->name,
                'status' => 'Approved',
                'reason' => 'Congratulations! Your KYC has been approved.',
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        }

        return redirect()->route('admin.kycs.index')->with('success', 'KYC approved successfully.');
    }

    public function reject(Request $request, Kyc $kyc)
    {
        if ($redirect = $this->authorizeAdmin()) {
            return $redirect;
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ]);

        $kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
        ]);

        if ($kyc->user && $kyc->user->email) {
            send_template_email('kyc-status-user', $kyc->user->email, [
                'user_name' => $kyc->user->name,
                'status' => 'Rejected',
                'reason' => $request->rejection_reason,
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        }

        return redirect()->route('admin.kycs.index')->with('error', 'KYC rejected.');
    }

}
