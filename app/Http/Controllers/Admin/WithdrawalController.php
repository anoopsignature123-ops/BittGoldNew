<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RocPayout;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user.kyc']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $withdrawals = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request already processed.');
        }

        $withdrawal->status = 'approved';
        $withdrawal->save();

        // Also update linked RocPayout status if this withdrawal belongs to an ROC auto-payout
        RocPayout::where('withdrawal_id', $withdrawal->id)->update(['status' => 'approved']);

        if ($withdrawal->user && $withdrawal->user->email) {
            send_template_email('withdrawal-status-user', $withdrawal->user->email, [
                'user_name' => $withdrawal->user->name,
                'status' => 'Approved',
                'amount' => number_format($withdrawal->payable_amount, 2),
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        }

        return back()->with('success', 'Withdrawal request approved successfully.');
    }

    public function reject($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request already processed.');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->status = 'rejected';
            $withdrawal->save();

            // Also update linked RocPayout status if this withdrawal belongs to an ROC auto-payout
            RocPayout::where('withdrawal_id', $withdrawal->id)->update(['status' => 'rejected']);

            // Refund amount back to user's earning wallet
            $user = $withdrawal->user;
            $user->earning_wallet += $withdrawal->amount;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => 'earning_wallet',
                'type' => 'credit',
                'amount' => $withdrawal->amount,
                'remark' => 'Refund after withdrawal rejection',
            ]);
        });

        if ($withdrawal->user && $withdrawal->user->email) {
            send_template_email('withdrawal-status-user', $withdrawal->user->email, [
                'user_name' => $withdrawal->user->name,
                'status' => 'Rejected',
                'amount' => number_format($withdrawal->amount, 2),
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        }

        return back()->with('success', 'Withdrawal request rejected and amount refunded to user wallet.');
    }
}