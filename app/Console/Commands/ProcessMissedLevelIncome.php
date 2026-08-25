<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Investment;
use App\Models\Income;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessDateLevelIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:process-date-level 
                            {--date=2026-08-23 : The activation date to process (Format: YYYY-MM-DD)}
                            {--dry-run : Run in simulation mode without updating wallets or creating records}
                            {--force : Force execute without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and credit missed level income for activations on a specific date according to MLM rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = $this->option('date');
        $isDryRun = $this->option('dry-run');
        $isForce = $this->option('force');

        try {
            $parsedDate = Carbon::parse($targetDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->error("Invalid date format provided: {$targetDate}. Please use YYYY-MM-DD format.");
            return 1;
        }

        $this->info("==================================================================");
        $this->info("          BITTGOLD LEVEL INCOME PROCESSOR FOR DATE: {$parsedDate}");
        $this->info("==================================================================");

        if ($isDryRun) {
            $this->warn(">>> RUNNING IN DRY-RUN MODE (SIMULATION ONLY - NO WALLETS WILL BE CREDITED) <<<");
        }

        // 1. Fetch active investments on target date
        $investments = Investment::with(['user', 'user.sponsor'])
            ->where('status', 'active')
            ->where(function ($query) use ($parsedDate) {
                $query->whereDate('created_at', $parsedDate)
                      ->orWhereDate('activated_at', $parsedDate);
            })
            ->orderBy('id', 'asc')
            ->get();

        if ($investments->isEmpty()) {
            $this->warn("No active investments found on {$parsedDate}.");
            return 0;
        }

        $this->info("Found " . $investments->count() . " active investment(s) on {$parsedDate}.");

        // 2. Define Level commission percentages (Notebook MLM Rules: L1=10%, L2=5%, L3=3%, L4=2%, L5-L30=1%)
        $levelPercentages = [
            1 => 10.00,
            2 => 5.00,
            3 => 3.00,
            4 => 2.00,
        ];
        for ($i = 5; $i <= 30; $i++) {
            $levelPercentages[$i] = 1.00;
        }

        $tableRows = [];
        $actionsToPerform = [];
        $totalCommissionToDistribute = 0;
        $alreadyCreditedCount = 0;

        foreach ($investments as $inv) {
            $buyer = $inv->user;
            if (!$buyer) {
                $this->error("Investment ID {$inv->id} has no associated user. Skipping.");
                continue;
            }

            $currentUpline = $buyer->sponsor;
            $level = 1;

            while ($currentUpline && $level <= 30) {
                if ($currentUpline->status === 'active') {
                    // Count active direct referrals of this upline
                    $activeDirectsCount = User::where('sponsor_id', $currentUpline->id)
                        ->where('status', 'active')
                        ->count();

                    // Check Direct Referral Eligibility per Level tier
                    $isEligible = false;
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
                        $commissionAmount = round(($inv->amount * $percentage) / 100, 2);

                        // Check if already paid/credited (Idempotency Protection)
                        $alreadyExists = Income::where('user_id', $currentUpline->id)
                            ->where('from_user_id', $buyer->id)
                            ->where('income_type', 'level')
                            ->where('level', $level)
                            ->where('package_amount', $inv->amount)
                            ->exists();

                        if ($alreadyExists) {
                            $status = '<fg=yellow>ALREADY CREDITED (SKIP)</>';
                            $alreadyCreditedCount++;
                        } else {
                            $status = $isDryRun ? '<fg=cyan>ELIGIBLE (DRY-RUN)</>' : '<fg=green>PENDING CREDIT</>';
                            if ($commissionAmount > 0) {
                                $totalCommissionToDistribute += $commissionAmount;
                                $actionsToPerform[] = [
                                    'investment' => $inv,
                                    'buyer' => $buyer,
                                    'upline' => $currentUpline,
                                    'level' => $level,
                                    'percentage' => $percentage,
                                    'amount' => $commissionAmount,
                                    'active_directs' => $activeDirectsCount,
                                ];
                            }
                        }

                        $tableRows[] = [
                            'Inv #' . $inv->id,
                            "{$buyer->name} (#{$buyer->id})",
                            '₹' . number_format($inv->amount, 2),
                            "{$currentUpline->name} (#{$currentUpline->id})",
                            "Level {$level}",
                            $activeDirectsCount,
                            "{$percentage}%",
                            '₹' . number_format($commissionAmount, 2),
                            $status,
                        ];
                    }
                }

                $currentUpline = $currentUpline->sponsor;
                $level++;
            }
        }

        $this->newLine();
        $this->table(
            ['Inv ID', 'Buyer', 'Package', 'Eligible Upline', 'Level', 'Directs', 'Rate', 'Commission', 'Status'],
            $tableRows
        );

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Total Activations on {$parsedDate}: " . $investments->count());
        $this->info("- Eligible Level Commissions to Process: " . count($actionsToPerform));
        $this->info("- Already Credited (Skipped): " . $alreadyCreditedCount);
        $this->info("- Total Amount to Distribute: ₹" . number_format($totalCommissionToDistribute, 2));
        $this->newLine();

        if (count($actionsToPerform) === 0) {
            $this->info("No new level commissions need to be processed for {$parsedDate}.");
            return 0;
        }

        if ($isDryRun) {
            $this->info("Dry-run complete. No changes were made to the database or wallets.");
            $this->info("To actually process and credit, run without the --dry-run option:");
            $this->comment("php artisan income:process-date-level --date={$parsedDate}");
            return 0;
        }

        // Ask for confirmation if not forced
        if (!$isForce) {
            if (!$this->confirm("Are you sure you want to credit ₹" . number_format($totalCommissionToDistribute, 2) . " across " . count($actionsToPerform) . " record(s)?", true)) {
                $this->warn("Operation cancelled by user.");
                return 0;
            }
        }

        $this->info("Processing and crediting level incomes...");
        $creditedCount = 0;
        $failedCount = 0;

        foreach ($actionsToPerform as $action) {
            $inv = $action['investment'];
            $buyer = $action['buyer'];
            $upline = $action['upline'];
            $level = $action['level'];
            $percentage = $action['percentage'];
            $commissionAmount = $action['amount'];

            DB::beginTransaction();
            try {
                // Lock user record for balance update
                $lockedUpline = User::lockForUpdate()->find($upline->id);
                if (!$lockedUpline) {
                    throw new \Exception("Upline User #{$upline->id} not found during lock.");
                }

                // 1. Credit Earning Wallet
                $lockedUpline->earning_wallet += $commissionAmount;
                $lockedUpline->save();

                // 2. Create Income Record
                Income::create([
                    'user_id' => $lockedUpline->id,
                    'from_user_id' => $buyer->id,
                    'income_type' => 'level',
                    'level' => $level,
                    'package_amount' => $inv->amount,
                    'percentage' => $percentage,
                    'amount' => $commissionAmount,
                    'created_at' => $inv->created_at ?? now(),
                    'updated_at' => now(),
                ]);

                // 3. Create Transaction Ledger Entry
                Transaction::create([
                    'user_id' => $lockedUpline->id,
                    'wallet_type' => 'earning_wallet',
                    'type' => 'credit',
                    'amount' => $commissionAmount,
                    'remark' => 'Level ' . $level . ' commission from ' . $buyer->name . ' (Activation Date: ' . $parsedDate . ')',
                    'created_at' => $inv->created_at ?? now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                $creditedCount++;
                $this->info("✔ Credited ₹{$commissionAmount} (Level {$level}) to {$lockedUpline->name} (#{$lockedUpline->id}) from {$buyer->name}");

            } catch (\Exception $e) {
                DB::rollBack();
                $failedCount++;
                $this->error("✖ Failed to credit Upline #{$upline->id}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("==================================================================");
        $this->info("                   PROCESSING COMPLETED!");
        $this->info("==================================================================");
        $this->info("Successfully credited: {$creditedCount} records.");
        if ($failedCount > 0) {
            $this->error("Failed credits: {$failedCount} records.");
        }
        $this->info("Total Amount Credited: ₹" . number_format($totalCommissionToDistribute, 2));

        return 0;
    }
}