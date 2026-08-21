<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Income;
use App\Services\MLMService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        return $this->loadDashboardData($user);
    }

    public function previewDashboard(User $user)
    {
        return $this->loadDashboardData($user);
    }

    private function loadDashboardData(User $user)
    {
        $userId = $user->id;
        $today = now()->toDateString();

        // 1. MLM Service se Power Leg aur Weaker Leg calculate karein (Jaise Rank Report mein aata hai)
        $mlmService = new MLMService();
        [$powerLegBusiness, $weakerLegBusiness] = $mlmService->calculateLegBusiness($user);

        // 2. Latest Leadership Salary fetch karein
        $latestLeadershipIncome = Income::where('user_id', $userId)
            ->where('income_type', 'leadership')
            ->latest('created_at')
            ->first();

        $leadershipDetails = [
            'amount' => $latestLeadershipIncome ? $latestLeadershipIncome->amount : 0,
            'month' => $latestLeadershipIncome ? Carbon::parse($latestLeadershipIncome->created_at)->format('F Y') : 'No Salary Credited Yet',
        ];

        // 3. Earnings data
        $earnings = [
            'referral' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'referral')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'referral')->sum('amount'),
            ],
            'level' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'level')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'level')->sum('amount'),
            ],
            'trade_profit' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'trade_profit')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'trade_profit')->sum('amount'),
            ],
            'leadership' => [
                'today' => Income::where('user_id', $userId)->where('income_type', 'leadership')->whereDate('created_at', $today)->sum('amount'),
                'total' => Income::where('user_id', $userId)->where('income_type', 'leadership')->sum('amount'),
            ],
        ];

        $totalEarned = Income::where('user_id', $userId)->sum('amount');
        $directReferralsCount = User::where('sponsor_id', $userId)->count();

        $activeInvestment = $user->investments()->where('status', 'active')->latest()->first();
        $activePackage = $activeInvestment ? number_format($activeInvestment->amount, 2) : 'No Package';

        // Chart data last 7 days
        $chartDays = [];
        $chartDaysTotal = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::now()->subDays($i)->format('D');
            $chartDays[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', $date)->sum('amount');
            $chartDaysTotal[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', '<=', $date)->sum('amount');
        }

        // 4. Ab saare variables view ko pass kar dein


        $kycStatus = $user->kyc()->latest()->first();
        return view('user.dashboard', [
            'user' => $user,
            'kycStatus' => $kycStatus ? $kycStatus->status : 'none',
            'earningWallet' => number_format($user->earning_wallet ?? 0, 2),
            'depositWallet' => number_format($user->deposit_wallet ?? 0, 2),
            'totalEarned' => number_format($totalEarned, 2),
            'activePackage' => $activePackage,
            'currentRank' => optional($user->rank)->name ?? 'Unranked',
            'powerLeg' => number_format($powerLegBusiness, 2),
            'weakerLeg' => number_format($weakerLegBusiness, 2),
            'leadershipDetails' => $leadershipDetails,
            'directReferrals' => $directReferralsCount,
            'earnings' => $earnings,
            'chartLabels' => array_values($chartLabels),
            'chartDays' => array_values($chartDays),
            'chartDaysTotal' => array_values($chartDaysTotal),
        ]);
    }
}