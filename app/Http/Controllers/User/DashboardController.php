<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Income;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $userId = $user->id;
        $today = now()->toDateString();

        // Earnings calculations from Income model
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
        $activePackage = $activeInvestment ? '' . number_format($activeInvestment->amount, 2) : 'No Package';

        // Chart data — last 7 days
        $chartDays      = [];
        $chartDaysTotal = [];
        $chartLabels    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[]    = Carbon::now()->subDays($i)->format('D');
            $chartDays[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', $date)->sum('amount');
            $chartDaysTotal[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', '<=', $date)->sum('amount');
        }

        return view('user.dashboard', [
            'user' => $user,
            'earningWallet' => number_format($user->earning_wallet ?? 0, 2),
            'depositWallet' => number_format($user->deposit_wallet ?? 0, 2),
            'totalEarned' => number_format($totalEarned, 2),
            'activePackage' => $activePackage,
            'currentRank' => optional($user->rank)->name ?? 'Unranked',
            'directReferrals' => $directReferralsCount,
            'earnings' => $earnings,
            'chartLabels'    => array_values($chartLabels),
            'chartDays'      => array_values($chartDays),
            'chartDaysTotal' => array_values($chartDaysTotal),
        ]);
    }

    public function previewDashboard(User $user)
    {
        $userId = $user->id;
        $today = now()->toDateString();

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
        $activePackage = $activeInvestment ? '' . number_format($activeInvestment->amount, 2) : 'No Package';
        $currentRank = optional($user->rank)->name ?? 'Unranked';

        // Chart data — last 7 days
        $chartDays      = [];
        $chartDaysTotal = [];
        $chartLabels    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[]    = Carbon::now()->subDays($i)->format('D');
            $chartDays[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', $date)->sum('amount');
            $chartDaysTotal[$date] = (float) Income::where('user_id', $userId)->whereDate('created_at', '<=', $date)->sum('amount');
        }

        return view('user.dashboard', [
            'user' => $user,
            'earningWallet' => number_format($user->earning_wallet ?? 0, 2),
            'depositWallet' => number_format($user->deposit_wallet ?? 0, 2),
            'totalEarned' => number_format($totalEarned, 2),
            'activePackage' => $activePackage,
            'currentRank' => $currentRank,
            'directReferrals' => $directReferralsCount,
            'earnings' => $earnings,
            'chartLabels'    => array_values($chartLabels),
            'chartDays'      => array_values($chartDays),
            'chartDaysTotal' => array_values($chartDaysTotal),
        ]);
    }
}