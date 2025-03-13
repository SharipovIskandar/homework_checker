<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('student_homework_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->jsonb('incorrect_answers');
            $table->integer('score');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('student_homework_results');
    }
};
