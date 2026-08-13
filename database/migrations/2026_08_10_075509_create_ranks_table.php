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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->integer('rank_no');
            $table->string('name');
            $table->decimal('power_leg_target', 16, 2); // e.g. 16,20,000
            $table->decimal('weaker_leg_target', 16, 2); // e.g. 40% of power leg
            $table->decimal('monthly_bonus', 16, 2); // e.g. 16,200
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};