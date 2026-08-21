<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessAutoWithdrawals extends Command
{
    protected $signature = 'withdrawals:process-auto';
    protected $description = 'Automatically process daily withdrawals from earning wallet for active users';

    public function handle()
    {
        $this->info('Starting automated daily withdrawals...');

        // Un sabhi users ko lo jinka earning wallet balance zero se zyada hai
        $users = User::where('earning_wallet', '>', 0)->get();
        $processedCount = 0;

        foreach ($users as $user) {
            $amountToWithdraw = (float) $user->earning_wallet;

            // Agar amount bohot kam hai (jaise < 10) toh skip kar sakte hain agar min limit ho
            if ($amountToWithdraw < 10) {
                continue;
            }

            DB::transaction(function () use ($user, $amountToWithdraw) {
                $fee = ($amountToWithdraw * 10) / 100; // 10% Fee deduction
                $payable = $amountToWithdraw - $fee;

                // 1. Earning wallet se minus karo
                $user->earning_wallet -= $amountToWithdraw;
                $user->save();

                // 2. Withdrawal request create karo
                // Note: Agar KYC approved nahi hai, tab bhi entry banegi taaki balance deduct ho jaye.
                // Hum ise view/controller mein filter kar denge ki unapproved KYC wale ko na dikhe.
                $withdrawal = Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $amountToWithdraw,
                    'fee' => $fee,
                    'payable_amount' => $payable,
                    'status' => 'pending',
                    'bank_details' => $user->wallet_address ?? 'Auto-Withdrawal Wallet', // User ka saved address/details
                ]);

                // 3. Ledger transaction log create karo
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_type' => 'earning_wallet',
                    'type' => 'debit',
                    'amount' => $amountToWithdraw,
                    'remark' => 'Daily auto-withdrawal processed (10% fee applied)',
                ]);
            });

            $processedCount++;
        }

        $this->info("Successfully processed auto-withdrawals for {$processedCount} users.");
    }
}