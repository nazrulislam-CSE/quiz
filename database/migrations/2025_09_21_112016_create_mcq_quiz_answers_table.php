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
        Schema::create('mcq_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mcq_id')->constrained('mcqs')->onDelete('cascade'); // question from mcqs table
            $table->foreignId('answer_id')->nullable()->constrained('mcq_answers')->onDelete('set null'); // user's selected answer
            $table->integer('time_taken')->nullable(); // time in seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_quiz_answers');
    }
};
