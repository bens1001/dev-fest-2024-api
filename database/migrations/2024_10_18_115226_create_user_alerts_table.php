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
        Schema::create('user_alerts', function (Blueprint $table) {
            $table->id('user_alert_id');
            $table->foreignId('user_id')->constrained('users','user_id')->onDelete('cascade');
            $table->foreignId('alert_id')->constrained('alerts','alert_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_alerts');
    }
};
