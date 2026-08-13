<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\Request;

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

        return back()->with('success', 'Deposit approved successfully. ' . number_format($deposit->amount, 2) . ' added to ' . $user->name . "'s deposit wallet.");
    }

    public function reject(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'This deposit request has already been processed.');
        }

        $deposit->status = 'rejected';
        $deposit->admin_remark = $request->input('admin_remark', 'Rejected by admin');
        $deposit->save();

        return back()->with('success', 'Deposit request has been rejected.');
    }
}