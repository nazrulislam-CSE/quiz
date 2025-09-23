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
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('model_test_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('paper_final_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('exam_datetime')->nullable();
            $table->integer('exam_duration')->nullable();
            $table->integer('exam_mark')->nullable();
            $table->integer('pass_mark')->nullable();
            $table->decimal('fee', 8, 2)->default(0.00)->nullable();
            $table->string('mcq_type')->comment('1=MCQ Topic Wise, 2=MCQ Study Question Topic Wise, 3=MCQ Paper Final Exam, 4=MCQ Model Test, 5=Manually MCQ');
            $table->text('question')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcqs');
    }
};
