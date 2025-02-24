<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('student_homeworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            $table->text('answers');
            $table->integer('score')->nullable();
            $table->enum('status', ['pending', 'checked'])->default('pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('student_homeworks');
    }
};
