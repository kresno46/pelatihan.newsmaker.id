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
            if (!Schema::hasColumn('folder_ebooks', 'api_id')) {
                $table->string('api_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('folder_ebooks', 'api_data')) {
                $table->json('api_data')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('folder_ebooks', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('updated_at');
            }
        });

        // Add API fields to ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            if (!Schema::hasColumn('ebooks', 'api_id')) {
                $table->string('api_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('ebooks', 'api_data')) {
                $table->json('api_data')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('ebooks', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('updated_at');
            }
            // folder_id already exists, no need to add it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop API fields from ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            if (Schema::hasColumn('ebooks', 'api_id')) {
                $table->dropColumn('api_id');
            }
            if (Schema::hasColumn('ebooks', 'api_data')) {
                $table->dropColumn('api_data');
            }
            if (Schema::hasColumn('ebooks', 'synced_at')) {
                $table->dropColumn('synced_at');
            }
        });

        // Drop API fields from folder_ebooks table
        Schema::table('folder_ebooks', function (Blueprint $table) {
            if (Schema::hasColumn('folder_ebooks', 'api_id')) {
                $table->dropColumn('api_id');
            }
            if (Schema::hasColumn('folder_ebooks', 'api_data')) {
                $table->dropColumn('api_data');
            }
            if (Schema::hasColumn('folder_ebooks', 'synced_at')) {
                $table->dropColumn('synced_at');
            }
        });
    }
};
