<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE apuppt_post_test_sessions MODIFY tipe ENUM('PATD','PATL','APUPPT') NOT NULL DEFAULT 'PATD'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE apuppt_post_test_sessions MODIFY tipe ENUM('PATD','PATL') NOT NULL DEFAULT 'PATD'");
        }
    }
};