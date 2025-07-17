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
        Schema::create('outlook_folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('outlooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('outlook_folders')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->string('cover')->nullable();
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlooks');
        Schema::dropIfExists('outlook_folders');
    }
};
