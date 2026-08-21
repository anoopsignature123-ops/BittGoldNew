<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Income;
use App\Models\Transaction;

class CleanupReferralIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlm:clean-referrals';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Deletes all referral incomes and transactions above Level 1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Incomes table se Level > 1 ki referral incomes delete karein
        $deletedIncomes = Income::where('income_type', 'referral')
            ->where('level', '>', 1)
            ->delete();

        // 2. Transactions table se Level 2 se Level 5 tak ki referral transactions delete karein
        $deletedTransactions = Transaction::where('remark', 'like', '%Referral commission Level%')
            ->where('remark', 'not like', '%Level 1%')
            ->delete();

        $this->info("Cleanup completed successfully!");
        $this->info("Deleted Incomes (Level > 1): {$deletedIncomes}");
        $this->info("Deleted Transactions (Level > 1): {$deletedTransactions}");
    }
}