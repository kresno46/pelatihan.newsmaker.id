<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_absensi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('apuppt_jadwal_absensis')->onDelete('cascade');
            $table->dateTime('waktu_absen');
            $table->timestamps();

            $table->unique(['user_id', 'jadwal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_absensi_logs');
    }
};