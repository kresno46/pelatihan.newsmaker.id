<?php

// database/migrations/2025_07_16_000000_create_certificate_awards_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificate_awards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('batch_number'); // Folder ID
            $table->double('average_score');
            $table->integer('total_ebooks');
            $table->uuid('certificate_uuid')->unique();
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_awards');
    }
};
