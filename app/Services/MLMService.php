<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Rank;
use App\Models\Transaction;
use App\Models\User;

class MLMService
{
    /**
     * Process Referral Income up to 5 levels.
     * Rule: 
     * - L1: 5%, L2: 4%, L3: 3%, L4: 2%, L5: 1%
     * - Each eligible upline must have at least 1 active direct referral.
     */
    public function distributeReferralIncome(User $buyer, float $investmentAmount)
    {
        $levelPercentages = [
            1 => 5.00,
            2 => 4.00,
            3 => 3.00,
            4 => 2.00,
            5 => 1.00,
        ];

        $currentSponsor = $buyer->sponsor;
        $level = 1;

        while ($currentSponsor && $level <= 5) {
            // Check if sponsor's account status is active
            if ($currentSponsor->status === 'active') {
                // Count active direct referrals for eligibility
                $activeDirectsCount = User::where('sponsor_id', $currentSponsor->id)
                    ->where('status', 'active')
                    ->count();

                if ($activeDirectsCount >= 1) {
                    $percentage = $levelPercentages[$level] ?? 1.00;
                    $commissionAmount = ($investmentAmount * $percentage) / 100;

                    if ($commissionAmount > 0) {
                        // Credit to user's earning wallet
                        $currentSponsor->earning_wallet += $commissionAmount;
                        $currentSponsor->save();

                        // Log income record
                        Income::create([
                            'user_id' => $currentSponsor->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'referral',
                            'level' => $level,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        // Log ledger transaction
                        Transaction::create([
                            'user_id' => $currentSponsor->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => 'Referral commission Level ' . $level . ' from ' . $buyer->name,
                        ]);
                    }
                }
            }

            $currentSponsor = $currentSponsor->sponsor;
            $level++;
        }
    }

    /**
     * Process Level Income up to 30 levels with Notebook Rules:
     * - L1: 10% (Requires >= 3 Directs)
     * - L2: 5% (Requires >= 6 Directs)
     * - L3: 3% (Requires >= 9 Directs)
     * - L4: 2% (Requires >= 10 Directs)
     * - L5-L30: 1% (Requires >= 11 Directs)
     */
    public function distributeLevelIncome(User $buyer, float $investmentAmount)
    {
        $levelPercentages = [
            1 => 10.00,
            2 => 5.00,
            3 => 3.00,
            4 => 2.00,
        ];

        // Automatically set 1.00% for levels 5 to 30
        for ($i = 5; $i <= 30; $i++) {
            $levelPercentages[$i] = 1.00;
        }

        $currentUpline = $buyer->sponsor;
        $level = 1;

        while ($currentUpline && $level <= 30) {
            if ($currentUpline->status === 'active') {
                $activeDirectsCount = User::where('sponsor_id', $currentUpline->id)
                    ->where('status', 'active')
                    ->count();

                $isEligible = false;

                // Check direct requirements per level tier
                if ($level == 1 && $activeDirectsCount >= 3) {
                    $isEligible = true;
                } elseif ($level == 2 && $activeDirectsCount >= 6) {
                    $isEligible = true;
                } elseif ($level == 3 && $activeDirectsCount >= 9) {
                    $isEligible = true;
                } elseif ($level == 4 && $activeDirectsCount >= 10) {
                    $isEligible = true;
                } elseif ($level >= 5 && $level <= 30 && $activeDirectsCount >= 11) {
                    $isEligible = true;
                }

                if ($isEligible) {
                    $percentage = $levelPercentages[$level] ?? 1.00;
                    $commissionAmount = ($investmentAmount * $percentage) / 100;

                    if ($commissionAmount > 0) {
                        // Credit to earning wallet
                        $currentUpline->earning_wallet += $commissionAmount;
                        $currentUpline->save();

                        // Log income entry
                        Income::create([
                            'user_id' => $currentUpline->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'level',
                            'level' => $level,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        // Log transaction ledger
                        Transaction::create([
                            'user_id' => $currentUpline->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => 'Level ' . $level . ' commission from ' . $buyer->name,
                        ]);
                    }
                }
            }

            $currentUpline = $currentUpline->sponsor;
            $level++;
        }
    }

    /**
     * Process Trade Profit Income based on team business and user rank (Notebook rules).
     * Percentages:
     * - Manager: 1.00%
     * - Sr Manager: 1.50%
     * - Director: 2.00%
     * - Executive Director: 2.50%
     * - Sapphire: 3.00%
     * - Diamond: 3.50%
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
                        // Credit commission to earning wallet
                        $currentUpline->earning_wallet += $commissionAmount;
                        $currentUpline->save();

                        // Create income record
                        Income::create([
                            'user_id' => $currentUpline->id,
                            'from_user_id' => $buyer->id,
                            'income_type' => 'trade_profit',
                            'level' => 1,
                            'package_amount' => $investmentAmount,
                            'percentage' => $percentage,
                            'amount' => $commissionAmount,
                        ]);

                        // Create transaction log
                        Transaction::create([
                            'user_id' => $currentUpline->id,
                            'wallet_type' => 'earning_wallet',
                            'type' => 'credit',
                            'amount' => $commissionAmount,
                            'remark' => "Trade profit ({$percentage}%) for rank {$rankName} from " . $buyer->name,
                        ]);
                    }
                }
            }

            $currentUpline = $currentUpline->sponsor;
        }
    }

    /**
     * Upgrade the selected upline and all of its uplines using complete branch business:
     * largest direct branch is the power leg and all remaining direct branches together form the weaker leg.
     */
    public function evaluateUserRank(?User $user)
    {
        while ($user) {
            $this->evaluateSingleUserRank($user);
            $user = $user->sponsor;
        }
    }

    /**
     * Evaluate single user rank progression based on Power Leg and Weaker Leg targets.
     */
    private function evaluateSingleUserRank(User $user): void
    {
        $ranks = Rank::orderByDesc('rank_no')->get();
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

    /**
     * Calculate power leg and weaker leg business from direct sponsor legs.
     */
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

    /**
     * Recursively calculate total active investment business under a branch.
     */
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