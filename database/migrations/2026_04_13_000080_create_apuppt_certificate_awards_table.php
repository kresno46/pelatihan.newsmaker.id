<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuppt_certificate_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_test_id')->constrained('apuppt_post_test_results')->onDelete('cascade');
            $table->decimal('average_score', 5, 2)->nullable();
            $table->uuid('certificate_uuid')->unique();
            $table->dateTime('awarded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuppt_certificate_awards');
    }
};