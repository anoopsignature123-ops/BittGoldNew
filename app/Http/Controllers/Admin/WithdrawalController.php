<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(10)->withQueryString();

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

        return back()->with('success', 'Withdrawal request rejected and amount refunded to user wallet.');
    }
}