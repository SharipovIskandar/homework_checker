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
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->jsonb('word')->nullable(); // {"hello": "salom", "world": "dunyo"}
            $table->string('level')->nullable(); // A1, A2, B1
            $table->integer('total_vocabularies')->nullable(); // A1, A2, B1
            $table->dateTime('due_date')->nullable(); // Test muddati
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
