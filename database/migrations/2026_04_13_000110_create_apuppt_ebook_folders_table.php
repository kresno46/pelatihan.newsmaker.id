<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_ebook_folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name', 255);
            $table->text('deskripsi')->nullable();
            $table->string('slug', 255)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_ebook_folders');
    }
};