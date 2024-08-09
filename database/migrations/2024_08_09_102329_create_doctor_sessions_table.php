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
        Schema::create('doctor_sessions', function (Blueprint $table) {
            $table->id('doctor_session_id');
            $table->integer('doctor_id');
            $table->integer('time_slot');
            $table->string('days');
            $table->time('start_time');
            $table->time('end_time');+
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_sessions');
    }
};
