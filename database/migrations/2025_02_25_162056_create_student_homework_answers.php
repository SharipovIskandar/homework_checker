<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('student_homework_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_homework_id')->constrained('student_homeworks')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('homework_questions')->onDelete('cascade');
            $table->jsonb('student_answer');
            $table->boolean('is_checked')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_homework_answers');
    }
};
