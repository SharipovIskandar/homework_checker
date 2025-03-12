<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->string('exercise_id')->nullable();
            $table->jsonb('task_condition')->nullable()->after('type_id');
            $table->foreignId('type_id')->constrained('homework_types');
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });

    }

    public function down() {
        Schema::dropIfExists('student_homeworks');
    }
};
