<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MLMService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DistributeMonthlyLeadershipBonus extends Command
{
    protected $signature = 'leadership:distribute';
    protected $description = 'Distribute monthly leadership salary based on ranks and 10% monthly growth rule';

    public function handle()
    {
        $this->info('Starting Monthly Leadership Salary Distribution...');

        $mlmService = new MLMService();
        $users = User::where('status', 'active')->whereNotNull('rank_id')->with('rank')->get();

        $currentMonthStart = now()->startOfMonth();
        $previousMonthStart = now()->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->copy()->subMonth()->endOfMonth();

        foreach ($users as $user) {
            $rank = $user->rank;
            if (!$rank || $rank->monthly_bonus <= 0) {
                continue;
            }

            // 1. Check if salary is already paid for this current month
            $alreadyPaid = Income::where('user_id', $user->id)
                ->where('income_type', 'leadership')
                ->where('created_at', '>=', $currentMonthStart)
                ->exists();

            if ($alreadyPaid) {
                continue;
            }

            // 2. Base Rank Eligibility Check (Power Leg & Weaker Leg Target)
            [$powerLegBiz, $weakerLegBiz] = $mlmService->calculateLegBusiness($user);
            $isRankEligible = ($powerLegBiz >= $rank->power_leg_target && $weakerLegBiz >= $rank->weaker_leg_target);

            if (!$isRankEligible) {
                continue; // Agar base target hi match nahi hua toh salary nahi milegi
            }

            // 3. 10% GROWTH LOGIC FOR SUBSEQUENT MONTHS
            // Check karo ki kya yeh user ko pehle kabhi leadership income mili hai?
            $hasReceivedBefore = Income::where('user_id', $user->id)
                ->where('income_type', 'leadership')
                ->exists();

            if ($hasReceivedBefore) {
                // Agar pehle mil chuki hai, toh pichle mahine ka total team business nikalenge
                $prevMonthBusiness = $user->investments()
                    ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                    ->sum('amount');

                // Ya agar aap total branch business ka pichla data track karte hain, toh uske hisab se:
                // Rule: Agle mahine ki salary ke liye pichle mahine ke mukable kam se kam 10% naya business hona chahiye
                // Man lijiye pichle mahine ka business $10,000 tha, toh is mahine kam se kam $11,000 ($10,000 + 10%) hona chahiye.

                // Yahan hum ek helper ya query se pichle mahine ke total team business ka target set kar sakte hain:
                // For safety, agar pichle mahine ka business 0 tha, toh rank target ko base man lenge.
                $requiredGrowthBusiness = $prevMonthBusiness > 0 ? ($prevMonthBusiness * 1.10) : ($rank->power_leg_target + $rank->weaker_leg_target);

                // Is mahine ka total current team business
                [$currPower, $currWeaker] = $mlmService->calculateLegBusiness($user);
                $currentTotalBusiness = $currPower + $currWeaker;

                // Agar current business required growth se kam hai, toh agle mahine ki salary hold ho jayegi
                if ($currentTotalBusiness < $requiredGrowthBusiness) {
                    $this->info("User ID {$user->id} skipped: 10% growth target not met for this month.");
                    continue;
                }
            }

            $salaryAmount = $rank->monthly_bonus;

            // 4. Credit Salary to Earning Wallet
            DB::transaction(function () use ($user, $salaryAmount) {
                $user->earning_wallet += $salaryAmount;
                $user->save();

                Income::create([
                    'user_id' => $user->id,
                    'from_user_id' => $user->id,
                    'income_type' => 'leadership',
                    'level' => 1,
                    'package_amount' => 0,
                    'percentage' => 0,
                    'amount' => $salaryAmount,
                ]);

                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_type' => 'earning_wallet',
                    'type' => 'credit',
                    'amount' => $salaryAmount,
                    'remark' => 'Monthly leadership salary credited for rank ' . $user->rank->name,
                ]);
            });

            $this->info("Leadership salary of \${$salaryAmount} distributed successfully to User ID: {$user->id}");
        }

        $this->info('Monthly leadership salary distribution completed.');
    }
}