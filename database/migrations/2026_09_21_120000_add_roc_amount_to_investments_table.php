<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (!Schema::hasColumn('investments', 'roc_amount')) {
                $table->decimal('roc_amount', 15, 2)->nullable()->after('amount');
            }
        });

        // Populate existing investments with roc_amount = amount * 1.5
        DB::statement("UPDATE investments SET roc_amount = amount * 1.5 WHERE roc_amount IS NULL");
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (Schema::hasColumn('investments', 'roc_amount')) {
                $table->dropColumn('roc_amount');
            }
        });
    }
};
