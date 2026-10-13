<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MLMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login')->with('error', 'Please login first.');
        }

        $investments = Investment::where('user_id', $user->id)->latest()->paginate(10);

        return view('user.investment.index', compact('user', 'investments'));
    }

    public function store(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        $amount = (float) $request->amount;

        if ($amount % 10000 !== 0) {
            return back()->with('error', 'Package investment must be in multiples of 10,000 (e.g., 10000, 20000, 30000).');
        }

        try {
            DB::transaction(function () use ($user, $amount) {
                $lockedUser = User::lockForUpdate()->findOrFail($user->id);
                $balance = (float) $lockedUser->deposit_wallet;
                if ($balance < $amount) {
                    throw new \DomainException('Insufficient deposit wallet balance. Available balance: ₹' . number_format($balance, 2) . '. Please add funds or enter a lower package amount.');
                }

                $lockedUser->deposit_wallet = $balance - $amount;
                $lockedUser->status = 'active';
                if (! $lockedUser->activated_at) $lockedUser->activated_at = now();
                $lockedUser->save();
                Transaction::create(['user_id' => $lockedUser->id, 'wallet_type' => 'deposit_wallet', 'type' => 'debit', 'amount' => $amount, 'remark' => 'Package investment purchase']);
                Investment::create(['user_id' => $lockedUser->id, 'amount' => $amount, 'roc_amount' => $amount * 1.5, 'activated_at' => now(), 'status' => 'active']);
            });
        } catch (\DomainException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        $mlmService = new MLMService();
        $user->refresh();
        $mlmService->evaluateUserRank($user->sponsor);
        $mlmService->distributeReferralIncome($user, $amount);
        $mlmService->distributeLevelIncome($user, $amount);
        $mlmService->distributeTradeProfitIncome($user, $amount);

        // Send investment confirmation email to user
        try {
            $latestInvestment = Investment::where('user_id', $user->id)->latest()->first();
            send_template_email('investment-confirmation', $user->email, [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'amount' => number_format($amount, 2),
                'investment_id' => $latestInvestment ? $latestInvestment->id : 'N/A',
                'activated_at' => now()->format('d M Y, h:i A'),
                'status' => 'Active',
                'site_name' => config('app.name'),
                'support_email' => config('mail.from.address', 'support@bittgold.com'),
                'logo' => asset('siteadmin/images/logo.png'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send investment confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('user.investment.index')->with('success', 'Successfully invested ' . number_format($amount, 2) . '! Your account is now active.');
    }

    public function cancel(Request $request, $id)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $investment = Investment::where('user_id', $user->id)->findOrFail($id);

        if ($investment->status !== 'active') {
            return back()->with('error', 'Only active investments can be refunded.');
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:1|max:' . $investment->amount,
        ]);

        $refundAmount = round((float) $request->refund_amount, 2);

        if ($refundAmount <= 0 || $refundAmount > $investment->amount) {
            return back()->with('error', 'Invalid refund amount. Maximum refundable: ₹' . number_format($investment->amount, 2));
        }

        $isFullRefund = ($refundAmount >= $investment->amount);

        try {
            DB::transaction(function () use ($user, $investment, $refundAmount, $isFullRefund) {
                if ($isFullRefund) {
                    // Full refund: mark status as cancelled
                    $investment->status = 'cancelled';
                    $investment->roc_amount = 0;
                } else {
                    // Partial refund: reduce investment amount and recalculate ROC target (1.5x)
                    $investment->amount = round($investment->amount - $refundAmount, 2);
                    $investment->roc_amount = round($investment->amount * 1.5, 2);
                }
                $investment->save();

                // Credit refund amount back to user's deposit_wallet
                $lockedUser = User::lockForUpdate()->findOrFail($user->id);
                $lockedUser->deposit_wallet = ($lockedUser->deposit_wallet ?? 0) + $refundAmount;
                $lockedUser->save();

                // Create Transaction Ledger entry
                Transaction::create([
                    'user_id' => $lockedUser->id,
                    'wallet_type' => 'deposit_wallet',
                    'type' => 'credit',
                    'amount' => $refundAmount,
                    'remark' => ($isFullRefund ? "Full refund" : "Partial refund of ₹" . number_format($refundAmount, 2)) . " for investment #INV-" . str_pad($investment->id, 5, '0', STR_PAD_LEFT),
                ]);

                if ($isFullRefund) {
                    // Cancel any pending ROC payouts for this investment
                    \App\Models\RocPayout::where('investment_id', $investment->id)
                        ->where('status', 'pending')
                        ->update(['status' => 'cancelled']);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }

        $msg = $isFullRefund
            ? 'Investment #INV-' . str_pad($investment->id, 5, '0', STR_PAD_LEFT) . ' has been fully cancelled and ₹' . number_format($refundAmount, 2) . ' refunded back to your Deposit Wallet!'
            : 'Partial refund of ₹' . number_format($refundAmount, 2) . ' processed successfully for Investment #INV-' . str_pad($investment->id, 5, '0', STR_PAD_LEFT) . '! Remaining active package: ₹' . number_format($investment->amount, 2);

        return back()->with('success', $msg);
    }
}