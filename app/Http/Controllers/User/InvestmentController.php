<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MLMService;
use Illuminate\Http\Request;

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

        $amount = $request->amount;

        if ($amount % 10000 !== 0) {
            return back()->with('error', 'Package investment must be in multiples of 10,000 (e.g., 10000, 20000, 30000).');
        }

        if ($user->deposit_wallet < $amount) {
            return back()->with('error', 'Insufficient balance in your deposit wallet. Please add funds first.');
        }

        $user->deposit_wallet -= $amount;

        $user->status = 'active';
        if (!$user->activated_at) {
            $user->activated_at = now();
        }
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'wallet_type' => 'deposit_wallet',
            'type' => 'debit',
            'amount' => $amount,
            'remark' => 'Package investment purchase',
        ]);

        Investment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'activated_at' => now(),
            'status' => 'active',
        ]);

        $mlmService = new MLMService();
        $mlmService->evaluateUserRank($user->sponsor);
        $mlmService->distributeReferralIncome($user, $amount);
        $mlmService->distributeLevelIncome($user, $amount);
        $mlmService->distributeTradeProfitIncome($user, $amount);
        
        return redirect()->route('user.investment.index')->with('success', 'Successfully invested ' . number_format($amount, 2) . '! Your account is now active.');
    }
}
