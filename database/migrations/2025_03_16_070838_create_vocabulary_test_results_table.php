<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vocabulary_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocabulary_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('correct_answers')->default(0);
            $table->jsonb('incorrect_answers')->nullable();
            $table->integer('total_vocabularies')->nullable()->default(0);
            $table->boolean('is_accepted')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary_test_results');
    }
};
