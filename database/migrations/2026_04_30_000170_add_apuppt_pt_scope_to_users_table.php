<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('apuppt_pt_scope', [
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('apuppt_pt_scope');
        });
    }
};
