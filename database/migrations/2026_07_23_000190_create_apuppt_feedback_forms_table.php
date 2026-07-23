<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_feedback_forms', function (Blueprint $table) {
            $table->id();
            $table->enum('apuppt_pt_scope', [
                'Trainer (RFB)',
                'Trainer (SGB)',
                'Trainer (KPF)',
                'Trainer (BPF)',
                'Trainer (EWF)',
            ])->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_feedback_forms');
    }
};
