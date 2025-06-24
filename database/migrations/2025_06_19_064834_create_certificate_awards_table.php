<?php

// database/migrations/xxxx_xx_xx_create_certificate_awards_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateAwardsTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('batch_number');
            $table->float('average_score');
            $table->integer('total_ebooks');
            $table->uuid('certificate_uuid')->unique();
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->unique(['user_id', 'batch_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_awards');
    }
}
