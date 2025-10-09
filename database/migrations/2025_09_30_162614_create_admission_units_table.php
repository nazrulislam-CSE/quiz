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
        Schema::create('admission_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_info_id')->constrained('admission_infos')->onDelete('cascade');
            $table->string('unit'); 
            $table->string('description')->nullable(); 
            $table->string('note')->nullable();
            $table->date('exam_date')->nullable(); 
            $table->time('exam_time')->nullable();
            $table->string('mark')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_units');
    }
};
