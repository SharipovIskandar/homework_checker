<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('homework_questions', function (Blueprint $table) {
            $table->id();
            $table->jsonb('tip')->nullable();
            $table->jsonb('answer_template')->nullable();
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            $table->jsonb('questions')->nullable();
            $table->jsonb('correct_answers');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homework_questions');
    }
};

