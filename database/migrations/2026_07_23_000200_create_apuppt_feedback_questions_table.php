<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_feedback_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('apuppt_feedback_forms')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['rating', 'essay']);
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_feedback_questions');
    }
};
