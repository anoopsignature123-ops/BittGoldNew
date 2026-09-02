<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Investment;
use App\Models\Income;
use App\Models\Transaction;
use App\Services\MLMService;

class DistributeMissedLevelIncome extends Command
{
    protected $signature = 'income:distribute-missed';
    protected $description = 'Sync inactive investors to active and distribute missed level income';

    public function handle(MLMService $mlmService)
    {
        $this->info('Step 1: Syncing inactive investors who have active investments...');

        // 1. Pehle sabhi inactive users ko active status mein sync karein
        $updatedCount = User::where('status', 'inactive')
            ->whereHas('investments', function($q) {
                $q->where('status', 'active');
            })->update(['status' => 'active']);

        $this->info("Synced {$updatedCount} inactive users to active status.");

        $this->info('Step 2: Checking and distributing missed level incomes...');

        $investments = Investment::where('status', 'active')->get();
        $count = 0;

        foreach ($investments as $investment) {
            $buyer = $investment->user;
            if (!$buyer || !$buyer->sponsor) continue;

            $investmentAmount = $investment->amount;
            $currentUpline = $buyer->sponsor;
            $level = 1;

            while ($currentUpline && $level <= 10) {
                // Upline active hona chahiye tabhi income milegi
                if ($currentUpline->status === 'active') {
                    
                    // Eligibility check
                    $activeDirectsCount = User::where('sponsor_id', $currentUpline->id)
                        ->where('status', 'active')->count();

                    $selfInvestment = $currentUpline->investments()
                        ->where('status', 'active')->sum('amount');

                    $isEligible = $this->checkEligibility($level, $activeDirectsCount, $selfInvestment);

                    if ($isEligible) {
                        // Check if already paid
                        $alreadyExists = Income::where('user_id', $currentUpline->id)
                            ->where('from_user_id', $buyer->id)
                            ->where('income_type', 'level')
                            ->where('level', $level)
                            ->where('package_amount', $investmentAmount)
                            ->exists();

                        if (!$alreadyExists) {
                            $this->creditIncome($currentUpline, $buyer, $level, $investmentAmount);
                            $count++;
                        }
                    }
                }
                $currentUpline = $currentUpline->sponsor;
                $level++;
            }
        }

        $this->info("Completed! Processed {$count} missed income records.");
    }

    private function checkEligibility($level, $directs, $investment) {
        if ($level == 1 && $directs >= 3) return true;
        if ($level == 2 && $directs >= 6 && $investment >= 50000) return true;
        if ($level == 3 && $directs >= 9 && $investment >= 100000) return true;
        if ($level == 4 && $directs >= 10 && $investment >= 200000) return true;
        if ($level >= 5 && $level <= 10 && $directs >= 11 && $investment >= 250000) return true;
        return false;
    }

    private function creditIncome($upline, $buyer, $level, $amount) {
        $percentages = [1=>10, 2=>8, 3=>3, 4=>2, 5=>2, 6=>1, 7=>1, 8=>1, 9=>1, 10=>1];
        $perc = $percentages[$level] ?? 1;
        $comm = ($amount * $perc) / 100;

        $upline->earning_wallet += $comm;
        $upline->save();

        Income::create([
            'user_id' => $upline->id, 'from_user_id' => $buyer->id,
            'income_type' => 'level', 'level' => $level,
            'package_amount' => $amount, 'percentage' => $perc, 'amount' => $comm
        ]);

        Transaction::create([
            'user_id' => $upline->id, 'wallet_type' => 'earning_wallet',
            'type' => 'credit', 'amount' => $comm,
            'remark' => "Missed Level $level commission from {$buyer->name}"
        ]);
    }
}