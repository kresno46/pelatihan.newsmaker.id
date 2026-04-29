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
            if (! Schema::hasColumn('folder_ebooks', 'api_id')) {
                $table->string('api_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('folder_ebooks', 'api_data')) {
                $table->json('api_data')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('folder_ebooks', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('updated_at');
            }
        });

        // Add API fields to ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            if (! Schema::hasColumn('ebooks', 'api_id')) {
                $table->string('api_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('ebooks', 'api_data')) {
                $table->json('api_data')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('ebooks', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('updated_at');
            }
            if (! Schema::hasColumn('ebooks', 'folder_id')) {
                $table->unsignedBigInteger('folder_id')->nullable()->after('id');
            }
            try {
                $table->foreign('folder_id')->references('id')->on('folder_ebooks')->onDelete('cascade');
            } catch (\Throwable $e) {
                // Ignore if foreign key already exists.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop API fields from ebooks table
        Schema::table('ebooks', function (Blueprint $table) {
            if (Schema::hasColumn('ebooks', 'folder_id')) {
                try {
                    $table->dropForeign(['folder_id']);
                } catch (\Throwable $e) {
                    // Ignore if foreign key does not exist.
                }
            }
            $columns = array_values(array_filter([
                Schema::hasColumn('ebooks', 'api_id') ? 'api_id' : null,
                Schema::hasColumn('ebooks', 'api_data') ? 'api_data' : null,
                Schema::hasColumn('ebooks', 'synced_at') ? 'synced_at' : null,
                Schema::hasColumn('ebooks', 'folder_id') ? 'folder_id' : null,
            ]));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        // Drop API fields from folder_ebooks table
        Schema::table('folder_ebooks', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('folder_ebooks', 'api_id') ? 'api_id' : null,
                Schema::hasColumn('folder_ebooks', 'api_data') ? 'api_data' : null,
                Schema::hasColumn('folder_ebooks', 'synced_at') ? 'synced_at' : null,
            ]));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
