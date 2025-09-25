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
        Schema::create('program_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');          
            $table->unsignedBigInteger('program_subject_id');  
            $table->string('topic_name')->nullable();
            $table->integer('total_mcq')->default(0);
            $table->integer('time')->default(0);           
            $table->decimal('exam_fee', 10, 2)->default(0);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('program_subject_id')->references('id')->on('program_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_topics');
    }
};
