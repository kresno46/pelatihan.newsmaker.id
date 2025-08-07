<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_absensis')->onDelete('cascade');
            $table->dateTime('waktu_absen');
            $table->timestamps();

            $table->unique(['user_id', 'jadwal_id']); // Mencegah absen ganda
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
