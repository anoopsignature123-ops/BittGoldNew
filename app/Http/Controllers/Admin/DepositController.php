<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = Deposit::with('user');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->latest()->paginate(10)->withQueryString();

        return view('admin.deposits.index', compact('deposits'));
    }

    public function approve($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'This deposit request has already been processed.');
        }

        // Update deposit status
        $deposit->status = 'approved';
        $deposit->save();

        // Credit amount to user's deposit wallet
        $user = $deposit->user;
        $user->deposit_wallet += $deposit->amount;
        $user->save();

        // Log transaction ledger entry
        Transaction::create([
            'user_id' => $user->id,
            'wallet_type' => 'deposit_wallet',
            'type' => 'credit',
            'amount' => $deposit->amount,
            'remark' => 'Deposit approved by admin',
        ]);

        // Send email notification to user
        try {
            send_template_email('deposit-status-user', $user->email, [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'amount' => number_format($deposit->amount, 2),
                'payment_method' => $deposit->payment_method ?? 'N/A',
                'reference_no' => $deposit->reference_no ?? 'N/A',
                'status' => 'Approved',
                'admin_remark' => '',
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit approval email to user: ' . $e->getMessage());
        }

        return back()->with('success', 'Deposit approved successfully. ' . number_format($deposit->amount, 2) . ' added to ' . $user->name . "'s deposit wallet.");
    }

    public function reject(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'This deposit request has already been processed.');
        }

        $adminRemark = $request->input('admin_remark', 'Rejected by admin');
        $deposit->status = 'rejected';
        $deposit->admin_remark = $adminRemark;
        $deposit->save();

        // Send email notification to user
        try {
            $user = $deposit->user;
            send_template_email('deposit-status-user', $user->email, [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'amount' => number_format($deposit->amount, 2),
                'payment_method' => $deposit->payment_method ?? 'N/A',
                'reference_no' => $deposit->reference_no ?? 'N/A',
                'status' => 'Rejected',
                'admin_remark' => $adminRemark,
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit rejection email to user: ' . $e->getMessage());
        }

        return back()->with('success', 'Deposit request has been rejected.');
    }
}