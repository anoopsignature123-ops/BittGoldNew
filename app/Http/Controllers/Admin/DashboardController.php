<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Income;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'admin') {
            return redirect()->route('admin.login');
        }

        $totalUsers = User::where('role_id', 2)->count();
        $activeUsers = User::where('status', 'active')->count();
        
        $totalDepositsWallet = User::sum('deposit_wallet');
        $totalCommission = Income::sum('amount');
        $totalInvestments = Investment::where('status', 'active')->sum('amount');
        $totalWithdrawals = Withdrawal::where('status', 'approved')->sum('payable_amount');
        $rankRewardsPaid = Income::where('income_type', 'leadership')->sum('amount');
        
        $pendingRequests = Deposit::where('status', 'pending')->count();

        $recentUsers = User::where('role_id', 2)->latest()->take(5)->get();
        $recentDeposits = Deposit::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', [
            'adminUser' => $user,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalDepositsWallet' => number_format($totalDepositsWallet, 2),
            'totalWithdrawals' => number_format($totalWithdrawals, 2),
            'totalCommission' => number_format($totalCommission, 2),
            'totalInvestments' => number_format($totalInvestments, 2),
            'rankRewardsPaid' => number_format($rankRewardsPaid, 2),
            'pendingRequests' => $pendingRequests,
            'recentUsers' => $recentUsers,
            'recentDeposits' => $recentDeposits,
        ]);
    }
}
