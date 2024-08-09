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
            $table->id('doctor_sessions_id');
            $table->integer('doctor_id');
            $table->string('time slot');
            $table->string('days');
            $table->string('start_time');
            $table->string('end_time');+
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
