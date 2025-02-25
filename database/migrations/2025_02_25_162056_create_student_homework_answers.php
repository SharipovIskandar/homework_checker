<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('student_homework_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('homework_submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('homework_questions')->onDelete('cascade');
            $table->jsonb('student_answer');
            $table->enum('is_checked', ['pending', 'checked', 'auto-checked'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_homework_answers');
    }
};
