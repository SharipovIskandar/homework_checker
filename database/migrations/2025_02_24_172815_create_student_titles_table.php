<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('student_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('title_id')->constrained('titles');
            $table->enum('status', ['active', 'challenged'])->default('active');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('student_titles');
    }
};
