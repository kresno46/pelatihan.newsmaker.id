<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'Admin',
                'Admin APUPPT',
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->default('Trainer (RFB)')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'Admin',
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->default('Trainer (RFB)')->change();
        });
    }
};
