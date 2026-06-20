<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('question_tag', function (Blueprint $table) {
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();

            $table->primary(['question_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_tag');
        Schema::dropIfExists('tags');
    }
};
