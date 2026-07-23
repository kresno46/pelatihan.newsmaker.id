<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('apuppt_feedback_forms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['form_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_feedback_responses');
    }
};
