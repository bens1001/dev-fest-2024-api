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
        Schema::create('energy_usage', function (Blueprint $table) {
            $table->id('usage_id');
            $table->foreignId('machine_id')->constrained('machines', 'machine_id')->onDelete('cascade');
            $table->float('energy_consumed');
            $table->dateTime('start_shift_time');
            $table->dateTime('end_shift_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_usages');
    }
};
