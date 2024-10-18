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
        Schema::create('productions', function (Blueprint $table) {
            $table->id('production_id');
            $table->foreignId('machine_id')->constrained('machines', 'machine_id')->onDelete('cascade');
            $table->date('start_time');
            $table->date('end_time');
            $table->integer('output_quantity');
            $table->integer('defects_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
