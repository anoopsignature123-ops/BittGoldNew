<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Jisko income mili
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete(); // Jis user ki wajah se income aayi (downline)
            $table->string('income_type'); // referral, level, trade_profit, leadership
            $table->integer('level')->default(1); // Level 1 to 5 / 10
            $table->decimal('package_amount', 16, 2); // Downline ka investment amount
            $table->decimal('percentage', 5, 2); // Commission percentage (e.g. 5.00)
            $table->decimal('amount', 16, 2); // Calculated commission amount in 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};