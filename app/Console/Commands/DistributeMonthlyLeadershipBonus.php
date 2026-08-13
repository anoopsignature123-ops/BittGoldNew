<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DistributeMonthlyLeadershipBonus extends Command
{
    protected $signature = 'leadership:distribute';
    protected $description = 'Distribute monthly leadership bonus to users based on their active ranks';

    public function handle()
    {
        $users = User::where('status', 'active')->whereNotNull('rank_id')->with('rank')->get();

        $monthStart = now()->startOfMonth();

        foreach ($users as $user) {
            if ($user->rank && $user->rank->monthly_bonus > 0) {
                $bonusAmount = $user->rank->monthly_bonus;

                $alreadyPaid = Income::where('user_id', $user->id)
                    ->where('income_type', 'leadership')
                    ->where('created_at', '>=', $monthStart)
                    ->exists();

                if ($alreadyPaid) {
                    continue;
                }

                DB::transaction(function () use ($user, $bonusAmount) {
                    $user->earning_wallet += $bonusAmount;
                    $user->save();

                    Income::create([
                        'user_id' => $user->id,
                        'from_user_id' => $user->id,
                        'income_type' => 'leadership',
                        'level' => 1,
                        'package_amount' => 0,
                        'percentage' => 0,
                        'amount' => $bonusAmount,
                    ]);

                    Transaction::create([
                        'user_id' => $user->id,
                        'wallet_type' => 'earning_wallet',
                        'type' => 'credit',
                        'amount' => $bonusAmount,
                        'remark' => 'Monthly leadership bonus',
                    ]);
                });
            }
        }

        $this->info('Monthly leadership bonuses distributed successfully.');
    }
}
