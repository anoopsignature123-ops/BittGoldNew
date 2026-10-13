<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\RocPayout;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Investment::with(['user.sponsor']);

        // Search by user or sponsor (name, email, referral code)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                })->orWhereHas('user.sponsor', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $investments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.investments.index', compact('investments'));
    }

    public function cancelAndRefund(Request $request, $id)
    {
        $investment = Investment::with('user')->findOrFail($id);

        if ($investment->status !== 'active') {
            return back()->with('error', 'Only active investments can be refunded.');
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:1|max:' . $investment->amount,
            'target_wallet' => 'nullable|string|in:deposit_wallet,earning_wallet',
        ]);

        $refundAmount = round((float) $request->refund_amount, 2);

        if ($refundAmount <= 0 || $refundAmount > $investment->amount) {
            return back()->with('error', 'Invalid refund amount. Maximum refundable: ₹' . number_format($investment->amount, 2));
        }

        $targetWallet = $request->input('target_wallet', 'deposit_wallet');
        if (!in_array($targetWallet, ['deposit_wallet', 'earning_wallet'])) {
            $targetWallet = 'deposit_wallet';
        }

        $isFullRefund = ($refundAmount >= $investment->amount);

        DB::transaction(function () use ($investment, $refundAmount, $targetWallet, $isFullRefund) {
            if ($isFullRefund) {
                // Full refund: mark status as cancelled
                $investment->status = 'cancelled';
                $investment->roc_amount = 0;
            } else {
                // Partial refund: reduce package amount and recalculate ROC target
                $investment->amount = round($investment->amount - $refundAmount, 2);
                $investment->roc_amount = round($investment->amount * 1.5, 2);
            }
            $investment->save();

            // Credit refund amount to user's selected wallet
            $user = $investment->user;
            if ($targetWallet === 'earning_wallet') {
                $user->earning_wallet = ($user->earning_wallet ?? 0) + $refundAmount;
            } else {
                $user->deposit_wallet = ($user->deposit_wallet ?? 0) + $refundAmount;
            }
            $user->save();

            // Record Transaction in ledger
            Transaction::create([
                'user_id' => $user->id,
                'wallet_type' => $targetWallet,
                'type' => 'credit',
                'amount' => $refundAmount,
                'remark' => ($isFullRefund ? "Full refund" : "Partial refund of ₹" . number_format($refundAmount, 2)) . " for investment #INV-" . str_pad($investment->id, 5, '0', STR_PAD_LEFT),
            ]);

            if ($isFullRefund) {
                // Cancel any pending ROC payouts for this investment
                RocPayout::where('investment_id', $investment->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'cancelled']);
            }
        });

        $walletName = $targetWallet === 'earning_wallet' ? 'Earning Wallet' : 'Deposit Wallet';
        $msg = $isFullRefund
            ? "Investment #INV-" . str_pad($investment->id, 5, '0', STR_PAD_LEFT) . " fully cancelled & ₹" . number_format($refundAmount, 2) . " refunded to {$investment->user->name}'s {$walletName}."
            : "Partial refund of ₹" . number_format($refundAmount, 2) . " processed for Investment #INV-" . str_pad($investment->id, 5, '0', STR_PAD_LEFT) . ". Remaining package amount: ₹" . number_format($investment->amount, 2);

        return back()->with('success', $msg);
    }
}