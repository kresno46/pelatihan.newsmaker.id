<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100); // Diperbesar untuk fleksibilitas
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable(); // Optional jika deskripsi kosong
            $table->string('cover'); // Gunakan string jika ini path/file name
            $table->string('file');  // Sama seperti cover
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
