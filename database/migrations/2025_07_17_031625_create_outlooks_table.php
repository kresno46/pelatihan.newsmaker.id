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
        Schema::create('outlooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('deskripsi');
            $table->string('cover');
            $table->string('file');
            $table->text('slug');
            $table->unsignedBigInteger('folderOutlook_id');
            $table->timestamps();

            $table->foreign('folderOutlook_id')
                ->references('id')
                ->on('folder_outlooks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlooks');
    }
};
