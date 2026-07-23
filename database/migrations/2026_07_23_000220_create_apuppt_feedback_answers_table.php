<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('apuppt_feedback_responses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('apuppt_feedback_questions')->onDelete('cascade');
            $table->unsignedTinyInteger('rating_value')->nullable();
            $table->text('answer_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_feedback_answers');
    }
};
