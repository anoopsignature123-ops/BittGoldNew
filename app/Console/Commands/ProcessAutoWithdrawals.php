<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessAutoWithdrawals extends Command
{
    protected $signature = 'withdrawals:process-auto {--date= : The date to process withdrawals for (Y-m-d)}';
    protected $description = 'Automatically process daily withdrawals from earning wallet for active users (Supports back-dating)';

    public function handle()
    {
        $inputDate = $this->option('date');
        $targetDate = $inputDate ? Carbon::parse($inputDate) : Carbon::today();

        $this->info("Starting automated withdrawals for date: " . $targetDate->toDateString() . "...");

        $users = User::where('earning_wallet', '>', 0)->get();
        $processedCount = 0;

        foreach ($users as $user) {
            $amountToWithdraw = (float) $user->earning_wallet;

            if ($amountToWithdraw < 10) {
                continue;
            }

            DB::transaction(function () use ($user, $amountToWithdraw, $targetDate) {
                $fee = ($amountToWithdraw * 10) / 100;
                $payable = $amountToWithdraw - $fee;

                $user->earning_wallet -= $amountToWithdraw;
                $user->save();

                $exactTimestamp = $targetDate->copy()->setTime(0, 0, 0);

                // 1. Withdrawal request manually create karo bina automatic timestamp ke
                $withdrawal = new Withdrawal();
                $withdrawal->user_id = $user->id;
                $withdrawal->amount = $amountToWithdraw;
                $withdrawal->fee = $fee;
                $withdrawal->payable_amount = $payable;
                $withdrawal->status = 'pending';
                $withdrawal->bank_details = $user->wallet_address ?? 'Auto-Withdrawal Wallet';
                $withdrawal->created_at = $exactTimestamp;
                $withdrawal->updated_at = $exactTimestamp;
                $withdrawal->timestamps = false; // Laravel ko automatic timestamp overwrite karne se rokenge
                $withdrawal->save();

                // 2. Transaction log manually create karo
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->wallet_type = 'earning_wallet';
                $transaction->type = 'debit';
                $transaction->amount = $amountToWithdraw;
                $transaction->remark = 'Daily auto-withdrawal processed (10% fee applied)';
                $transaction->created_at = $exactTimestamp;
                $transaction->updated_at = $exactTimestamp;
                $transaction->timestamps = false;
                $transaction->save();
            });

            $processedCount++;
        }

        $this->info("Successfully processed auto-withdrawals for {$processedCount} users on date {$targetDate->toDateString()}.");
    }
}