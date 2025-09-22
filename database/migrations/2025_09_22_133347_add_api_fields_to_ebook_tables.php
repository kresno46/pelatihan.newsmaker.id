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
        // Add API fields to folder_ebooks table
        Schema::table('folder_ebooks', function (Blueprint $table) {
            $table->string('api_id')->nullable()->after('id');
            $table->json('api_data')->nullable()->after('slug');
            $table->timestamp('synced_at')->nullable()->after('updated_at');
        });

        // Add API fields to ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('api_id')->nullable()->after('id');
            $table->json('api_data')->nullable()->after('slug');
            $table->timestamp('synced_at')->nullable()->after('updated_at');
            $table->unsignedBigInteger('folder_id')->nullable()->after('id');
            $table->foreign('folder_id')->references('id')->on('folder_ebooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop API fields from ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn(['api_id', 'api_data', 'synced_at', 'folder_id']);
        });

        // Drop API fields from folder_ebooks table
        Schema::table('folder_ebooks', function (Blueprint $table) {
            $table->dropColumn(['api_id', 'api_data', 'synced_at']);
        });
    }
};
