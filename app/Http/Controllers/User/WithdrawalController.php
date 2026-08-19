<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = $this->authenticatedUser();
        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $kycApproved = $user->kyc()->where('status', 'approved')->exists();
        $withdrawals = Withdrawal::where('user_id', $user->id)->latest()->paginate(10);

        return view('user.withdrawal.index', compact('user', 'withdrawals', 'kycApproved'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'bank_details' => 'required|string|max:255',
        ]);

        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $kycApproved = $user->kyc()->where('status', 'approved')->exists();

        if (!$kycApproved) {
            return back()->with('error', 'Withdrawal is allowed only after your KYC is approved.');
        }

        if ($user->earning_wallet < $request->amount) {
            return back()->with('error', 'Insufficient balance in your earning wallet.');
        }

        DB::transaction(function () use ($user, $request) {
            $fee = ($request->amount * 10) / 100; // 10% Fee deduction
            $payable = $request->amount - $fee;

            // Deduct from earning wallet
            $user->earning_wallet -= $request->amount;
            $user->save();

            // Create withdrawal request
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'fee' => $fee,
                'payable_amount' => $payable,
                'status' => 'pending',
                'bank_details' => $request->bank_details,
            ]);

            // Log Transaction Ledger
            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => 'earning_wallet',
                'type' => 'debit',
                'amount' => $request->amount,
                'remark' => 'Withdrawal request submitted (10% fee applied)',
            ]);
        });

        $adminEmails = \App\Models\User::whereHas('role', fn($query) => $query->where('slug', 'admin'))
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        if (empty($adminEmails)) {
            $adminEmails = [config('mail.from.address', 'admin@bittgold.com')];
        }

        send_template_email('withdrawal-submitted-admin', $adminEmails, [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'member_id' => $user->unique_id ?? $user->referral_code ?? 'N/A',
            'referral_code' => $user->referral_code ?? $user->unique_id ?? 'N/A',
            'amount' => number_format($request->amount, 2),
            'bank_details' => $request->bank_details,
            'site_name' => config('app.name'),
            'support_email' => config('mail.from.address', 'support@bittgold.com'),
            'logo' => asset('siteadmin/images/logo.png'),
        ]);

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }
}