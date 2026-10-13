<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (!Schema::hasColumn('investments', 'roc_payouts_count')) {
                $table->unsignedTinyInteger('roc_payouts_count')->default(0)->after('status');
            }
            if (!Schema::hasColumn('investments', 'last_roc_payout_at')) {
                $table->timestamp('last_roc_payout_at')->nullable()->after('roc_payouts_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (Schema::hasColumn('investments', 'roc_payouts_count')) {
                $table->dropColumn('roc_payouts_count');
            }
            if (Schema::hasColumn('investments', 'last_roc_payout_at')) {
                $table->dropColumn('last_roc_payout_at');
            }
        });
    }
};
