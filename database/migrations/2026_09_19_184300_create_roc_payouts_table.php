<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roc_payouts')) {
            Schema::create('roc_payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('investment_id')->constrained('investments')->onDelete('cascade');
                $table->unsignedBigInteger('withdrawal_id')->nullable();
                $table->unsignedTinyInteger('installment_no'); // 1 to 6
                $table->decimal('amount', 15, 2);
                $table->string('status')->default('pending'); // pending, approved, processed
                $table->timestamp('payout_date');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('roc_payouts');
    }
};
