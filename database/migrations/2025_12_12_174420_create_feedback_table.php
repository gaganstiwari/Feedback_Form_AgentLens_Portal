<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedTinyInteger('nps_score')->nullable();
            $table->json('feedback')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->enum('status', ['open', 'close', 'wip'])->default('open');
            $table->unsignedTinyInteger('viewed_count')->default(0);
            $table->string('mode')->default('web_form');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
