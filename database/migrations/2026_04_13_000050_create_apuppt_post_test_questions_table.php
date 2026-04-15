<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_post_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('apuppt_post_test_sessions')->onDelete('cascade');
            $table->text('question');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c')->nullable();
            $table->text('option_d')->nullable();
            $table->enum('correct_option', ['A', 'B', 'C', 'D']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_post_test_questions');
    }
};