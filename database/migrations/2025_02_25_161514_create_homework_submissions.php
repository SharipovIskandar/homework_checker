<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            $table->jsonb('answers');
            $table->string('status')->default('pending');
            $table->integer('score')->nullable();
            $table->integer('total_questions')->nullable();
            $table->integer('correct_answers')->nullable();
            $table->boolean('is_accepted')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('homework_submissions');
    }
};
