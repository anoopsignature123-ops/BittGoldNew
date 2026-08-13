<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete() ;
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->nullOnDelete() ;
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('plain_password')->nullable();
            $table->string('referral_code')->nullable()->unique();
            $table->string('mobile')->nullable();
            $table->string('country_code')->nullable();
            $table->string('image')->nullable();
            $table->decimal('matched_bv', 16, 2)->default(0);
            $table->enum('status', ['inactive', 'active'])->default('inactive');
            $table->integer('current_rank_no')->default(0);
            $table->string('current_rank_name')->nullable();
            $table->decimal('deposit_wallet', 16, 2)->default(0);  
            $table->decimal('earning_wallet', 16, 2)->default(0);
            $table->string('active_plan')->default('No Active Plan');
            $table->timestamp('activated_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};