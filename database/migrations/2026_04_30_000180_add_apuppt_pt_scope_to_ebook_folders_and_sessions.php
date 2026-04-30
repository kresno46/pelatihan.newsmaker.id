<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apuppt_ebook_folders', function (Blueprint $table) {
            $table->enum('apuppt_pt_scope', [
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->nullable()->after('is_active');
        });

        Schema::table('apuppt_post_test_sessions', function (Blueprint $table) {
            $table->enum('apuppt_pt_scope', [
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->nullable()->after('ebook_id');
        });
    }

    public function down(): void
    {
        Schema::table('apuppt_post_test_sessions', function (Blueprint $table) {
            $table->dropColumn('apuppt_pt_scope');
        });

        Schema::table('apuppt_ebook_folders', function (Blueprint $table) {
            $table->dropColumn('apuppt_pt_scope');
        });
    }
};
