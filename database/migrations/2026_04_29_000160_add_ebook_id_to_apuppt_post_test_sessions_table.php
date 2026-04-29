<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apuppt_post_test_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('apuppt_post_test_sessions', 'ebook_id')) {
                $table->foreignId('ebook_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('apuppt_ebooks')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('apuppt_post_test_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('apuppt_post_test_sessions', 'ebook_id')) {
                try {
                    $table->dropForeign(['ebook_id']);
                } catch (\Throwable $e) {
                    // Ignore if foreign key already removed.
                }
                $table->dropColumn('ebook_id');
            }
        });
    }
};
