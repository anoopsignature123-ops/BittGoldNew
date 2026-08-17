<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Transaction;
use App\Models\User;

class MLMService
{
    /**
     * Process referral income strictly for Level 1 only.
     * Notebook rule: every eligible upline must have at least one active direct.
     */
    public function distributeReferralIncome(User $buyer, float $investmentAmount)
    {
        $currentSponsor = $buyer->sponsor;
        $level = 1;

        if ($currentSponsor && $level === 1) {
            if ($currentSponsor->status === 'active') {
                $activeDirectsCount = User::where('sponsor_id', $currentSponsor->id)
                    ->where('status', 'active')
                    ->count();

                if ($activeDirectsCount >= 1) {
                    $percentage = 5.00;
                    $commissionAmount = ($investmentAmount * $percentage) / 100;

                    if ($commissionAmount > 0) {
                        $currentSponsor->earning_wallet += $commissionAmount;
                        $currentSponsor->save();

                        Income::create([
                            'user_id' => $currentSponsor->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'referral',
                            'level' => $level,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        Transaction::create([
                            'user_id' => $currentSponsor->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => 'Referral commission from ' . $buyer->name,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Process Level Income up to 10 levels with Notebook Rules:
     * L1: 3 Directs, Self-Investment >= 25,000
     * L2: 6 Directs, Self-Investment >= 50,000
     * L3: 9 Directs, Self-Investment >= 100,000 (1 Lac)
     * L4: 10 Directs, Self-Investment >= 200,000 (2 Lac)
     * L5-L10: 11 Directs, Self-Investment >= 250,000 (2.5 Lac)
     */
    public function distributeLevelIncome(User $buyer, float $investmentAmount)
    {
        $levelPercentages = [
            1 => 10.00,
            2 => 8.00,
            3 => 3.00,
            4 => 2.00,
            5 => 2.00,
            6 => 1.00,
            7 => 1.00,
            8 => 1.00,
            9 => 1.00,
            10 => 1.00,
        ];

        $currentUpline = $buyer->sponsor;
        $level = 1;

        while ($currentUpline && $level <= 10) {
            if ($currentUpline->status === 'active') {
                $activeDirectsCount = User::where('sponsor_id', $currentUpline->id)
                    ->where('status', 'active')
                    ->count();

                $selfInvestment = $currentUpline->investments()
                    ->where('status', 'active')
                    ->sum('amount');

                $isEligible = false;

                if ($level == 1 && $activeDirectsCount >= 3 && $selfInvestment >= 25000) {
                    $isEligible = true;
                } elseif ($level == 2 && $activeDirectsCount >= 6 && $selfInvestment >= 50000) {
                    $isEligible = true;
                } elseif ($level == 3 && $activeDirectsCount >= 9 && $selfInvestment >= 100000) {
                    $isEligible = true;
                } elseif ($level == 4 && $activeDirectsCount >= 10 && $selfInvestment >= 200000) {
                    $isEligible = true;
                } elseif ($level >= 5 && $level <= 10 && $activeDirectsCount >= 11 && $selfInvestment >= 250000) {
                    $isEligible = true;
                }

                if ($isEligible) {
                    $percentage = $levelPercentages[$level] ?? 1.00;
                    $commissionAmount = ($investmentAmount * $percentage) / 100;

                    if ($commissionAmount > 0) {
                        $currentUpline->earning_wallet += $commissionAmount;
                        $currentUpline->save();

                        Income::create([
                            'user_id' => $currentUpline->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'level',
                            'level' => $level,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        Transaction::create([
                            'user_id' => $currentUpline->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => 'Level commission from ' . $buyer->name,
                        ]);
                    }
                }
            }

            $currentUpline = $currentUpline->sponsor;
            $level++;
        }
    }

    /**
     * Process Trade Profit Income based on team business and user rank.
     */
    public function distributeTradeProfitIncome(User $buyer, float $investmentAmount)
    {
        $rankPercentages = [
            'Manager' => 1.00,
            'Sr Manager' => 1.50,
            'Director' => 2.00,
            'Executive Director' => 2.50,
            'Sapphire' => 3.00,
            'Diamond' => 3.50,
        ];

        $currentUpline = $buyer->sponsor;

        while ($currentUpline) {
            $rankName = optional($currentUpline->rank)->name ?? $currentUpline->current_rank_name;

            if ($currentUpline->status === 'active' && !empty($rankName)) {
                if (array_key_exists($rankName, $rankPercentages)) {
                    $percentage = $rankPercentages[$rankName];
                    $commissionAmount = ($investmentAmount * $percentage) / 100;

                    if ($commissionAmount > 0) {
                        $currentUpline->earning_wallet += $commissionAmount;
                        $currentUpline->save();

                        Income::create([
                            'user_id' => $currentUpline->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'trade_profit',
                            'level' => 1,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        Transaction::create([
                            'user_id' => $currentUpline->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => 'Trade profit commission from ' . $buyer->name,
                        ]);
                    }
                }
            }

            $currentUpline = $currentUpline->sponsor;
        }
    }

    /**
     * Upgrade the selected upline and all of its uplines using complete branch
     * business: largest direct branch is the power leg and all remaining direct
     * branches together form the weaker leg.
     */
    public function evaluateUserRank(?User $user)
    {
        while ($user) {
            $this->evaluateSingleUserRank($user);
            $user = $user->sponsor;
        }
    }

    private function evaluateSingleUserRank(User $user): void
    {
        $ranks = \App\Models\Rank::orderByDesc('rank_no')->get();
        [$powerLegBusiness, $weakerLegBusiness] = $this->calculateLegBusiness($user);
        $currentRankNo = $user->rank?->rank_no ?? 0;

        foreach ($ranks as $rank) {
            if ($currentRankNo >= $rank->rank_no) {
                continue;
            }

            if ($powerLegBusiness >= $rank->power_leg_target && $weakerLegBusiness >= $rank->weaker_leg_target) {
                $user->rank_id = $rank->id;
                $user->current_rank_no = $rank->rank_no;
                $user->current_rank_name = $rank->name;
                $user->save();
                break;
            }
        }
    }

    public function calculateLegBusiness(User $user): array
    {
        $legBusiness = [];
        $visited = [];

        foreach (User::where('sponsor_id', $user->id)->get() as $direct) {
            $legBusiness[] = $this->branchBusiness($direct, $visited);
        }

        rsort($legBusiness);

        return [
            $legBusiness[0] ?? 0,
            array_sum(array_slice($legBusiness, 1)),
        ];
    }

    private function branchBusiness(User $user, array &$visited): float
    {
        if (isset($visited[$user->id])) {
            return 0;
        }

        $visited[$user->id] = true;
        $business = (float) $user->investments()->where('status', 'active')->sum('amount');

        foreach (User::where('sponsor_id', $user->id)->get() as $downline) {
            $business += $this->branchBusiness($downline, $visited);
        }

        return $business;
    }
}