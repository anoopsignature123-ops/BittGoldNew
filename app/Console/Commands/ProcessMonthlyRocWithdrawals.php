<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\RocPayout;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessMonthlyRocWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roc:process-monthly {--date= : The date to process ROC payouts for (YYYY-MM-DD)} {--ignore-kyc : Bypass KYC approval check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically process 1/6th monthly ROC auto-withdrawals for active investments with approved KYC (1 month after activation)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateParam = $this->option('date');
        $ignoreKyc = $this->option('ignore-kyc');
        $targetDate = $dateParam ? Carbon::parse($dateParam)->endOfDay() : Carbon::now()->endOfDay();

        $this->info("==================================================");
        $this->info("   BITTGOLD PRODUCTION ROC PAYOUT PROCESSING     ");
        $this->info("==================================================");
        $this->info("Target Date: " . $targetDate->toDateTimeString());
        $this->info("KYC Requirement: " . ($ignoreKyc ? "BYPASSED (--ignore-kyc)" : "ENFORCED (Approved KYC Only)"));

        // 1. Production Safety: Auto-backfill roc_amount for any investments where it's not set
        $backfilled = Investment::whereNull('roc_amount')->orWhere('roc_amount', '<=', 0)->update([
            'roc_amount' => DB::raw('amount * 1.5')
        ]);
        if ($backfilled > 0) {
            $this->warn("Auto-backfilled roc_amount (1.5x) for {$backfilled} investment records.");
        }

        // 2. Fetch active investments that haven't reached 6 payouts yet
        $investments = Investment::with(['user.kyc'])
            ->where('status', 'active')
            ->where('roc_payouts_count', '<', 6)
            ->get();

        $processedCount = 0;
        $totalAmountProcessed = 0;

        foreach ($investments as $inv) {
            // Safety fallback for activation date
            $activationTimestamp = $inv->activated_at ?? $inv->created_at;
            if (!$activationTimestamp) {
                continue;
            }

            // KYC Rule Check (Same as normal withdrawals)
            $userKyc = $inv->user->kyc;
            $isKycApproved = $userKyc && $userKyc->status === 'approved';

            if (!$ignoreKyc && !$isKycApproved) {
                $statusStr = $userKyc ? $userKyc->status : 'none';
                $this->warn(" [!] Inv #{$inv->id} | User #{$inv->user_id} ({$inv->user->name}) | KYC: '{$statusStr}' - SKIPPED (Approved KYC Required)");
                continue;
            }

            while ($inv->roc_payouts_count < 6) {
                $activationDate = Carbon::parse($activationTimestamp);
                $nextInstallmentNo = $inv->roc_payouts_count + 1;
                
                // Calculate exact 1-month interval date for the next installment
                $eligibleDate = $activationDate->copy()->addMonths($nextInstallmentNo);

                if ($targetDate->gte($eligibleDate)) {
                    DB::transaction(function () use ($inv, $nextInstallmentNo, $eligibleDate, &$processedCount, &$totalAmountProcessed) {
                        // Ensure roc_amount exists
                        if (!$inv->roc_amount || $inv->roc_amount <= 0) {
                            $inv->roc_amount = $inv->amount * 1.5;
                        }

                        $totalRocTarget = $inv->roc_amount;
                        $payoutAmount = round($totalRocTarget / 6, 2);

                        // Build user bank details string
                        $userKyc = $inv->user->kyc;
                        if ($userKyc && ($userKyc->bank_name || $userKyc->account_number)) {
                            $bankDetailsStr = "Bank: {$userKyc->bank_name}, A/C: {$userKyc->account_number}, IFSC: {$userKyc->ifsc_code}, Holder: {$userKyc->account_holder_name} (ROC Month {$nextInstallmentNo}/6 for Inv #{$inv->id})";
                        } else {
                            $bankDetailsStr = "ROC Auto-Withdrawal Month {$nextInstallmentNo}/6 for Inv #{$inv->id}";
                        }

                        // 1. Create Withdrawal Payout Request
                        $withdrawal = Withdrawal::create([
                            'user_id' => $inv->user_id,
                            'amount' => $payoutAmount,
                            'fee' => 0,
                            'payable_amount' => $payoutAmount,
                            'status' => 'pending',
                            'bank_details' => $bankDetailsStr,
                            'created_at' => $eligibleDate,
                            'updated_at' => now(),
                        ]);

                        // 2. Create RocPayout record
                        RocPayout::create([
                            'user_id' => $inv->user_id,
                            'investment_id' => $inv->id,
                            'withdrawal_id' => $withdrawal->id,
                            'installment_no' => $nextInstallmentNo,
                            'amount' => $payoutAmount,
                            'status' => 'pending',
                            'payout_date' => $eligibleDate,
                            'created_at' => $eligibleDate,
                            'updated_at' => now(),
                        ]);

                        // 3. Log Ledger Entry
                        Transaction::create([
                            'user_id' => $inv->user_id,
                            'wallet_type' => 'roc_wallet',
                            'type' => 'debit',
                            'amount' => $payoutAmount,
                            'remark' => "ROC Monthly Auto-Withdrawal (Month {$nextInstallmentNo}/6) processed",
                            'created_at' => $eligibleDate,
                            'updated_at' => now(),
                        ]);

                        // 4. Log Income Entry (ROC Income)
                        \App\Models\Income::create([
                            'user_id' => $inv->user_id,
                            'from_user_id' => $inv->user_id,
                            'income_type' => 'roc',
                            'level' => $nextInstallmentNo,
                            'package_amount' => $inv->amount,
                            'percentage' => 25.00,
                            'amount' => $payoutAmount,
                            'created_at' => $eligibleDate,
                            'updated_at' => now(),
                        ]);

                        // 5. Update Investment tracking
                        $inv->roc_payouts_count = $nextInstallmentNo;
                        $inv->last_roc_payout_at = now();
                        if ($nextInstallmentNo >= 6) {
                            $inv->status = 'completed';
                        }
                        $inv->save();

                        $processedCount++;
                        $totalAmountProcessed += $payoutAmount;

                        $this->line(" [✓] Inv #{$inv->id} | User #{$inv->user_id} ({$inv->user->name}) | Month {$nextInstallmentNo}/6 | Payout: ₹{$payoutAmount} | Due: {$eligibleDate->toDateString()}");
                    });
                } else {
                    // Next installment is in the future
                    break;
                }
            }
        }

        $this->info("--------------------------------------------------");
        $this->info("SUCCESS: Processed {$processedCount} monthly ROC payouts total ₹" . number_format($totalAmountProcessed, 2));
        $this->info("--------------------------------------------------");
        return Command::SUCCESS;
    }
}
