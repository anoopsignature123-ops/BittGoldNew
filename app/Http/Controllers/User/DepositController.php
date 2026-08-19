<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
     
    public function index()
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login')->with('error', 'Please login first.');
        }

        $deposits = Deposit::where('user_id', $user->id)->latest()->paginate(10);

        return view('user.deposit.index', compact('user', 'deposits'));
    }

    public function store(Request $request)
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'required|string|max:255',
            'payment_method' => ['required', 'string'],
        ]);

        $paymentSelection = $request->input('payment_method');

        if (! preg_match('/^(qr|upi|bank):(\d+)$/', $paymentSelection, $matches)) {
            return back()->withInput()->withErrors(['payment_method' => 'Please select a valid payment method.']);
        }

        [, $type, $id] = $matches;
        $method = \App\Models\PaymentMethod::whereKey($id)->where('type', $type)->where('is_active', true)->first();

        if (! $method) {
            return back()->withInput()->withErrors(['payment_method' => 'Please select an active payment method.']);
        }

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'reference_no' => $request->reference_no,
            'payment_method' => $method->title,
            'payment_details' => $paymentSelection,
            'status' => 'pending',
        ]);

        // Send email notification to admin (role_id = 1 is admin)
        try {
            $admins = User::where('role_id', 1)->get();

            if ($admins->isNotEmpty()) {
                foreach ($admins as $admin) {
                    send_template_email('deposit-submitted-admin', $admin->email, [
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'member_id' => $user->id,
                        'referral_code' => $user->referral_code ?? 'N/A',
                        'amount' => number_format($deposit->amount, 2),
                        'payment_method' => $method->title,
                        'reference_no' => $deposit->reference_no,
                        'site_name' => config('app.name'),
                        'logo' => asset('siteadmin/images/logo.png'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the deposit request
            Log::error('Failed to send deposit notification email to admin: ' . $e->getMessage());
        }

        return redirect()->route('user.deposit.index')->with('success', 'Deposit request of ₹' . number_format($request->amount, 2) . ' submitted successfully. Waiting for admin approval.');
    }

}