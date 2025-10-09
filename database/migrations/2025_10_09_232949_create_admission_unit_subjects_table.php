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
        Schema::create('admission_unit_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_unit_id')->constrained('admission_units')->onDelete('cascade');
            $table->string('subject');
            $table->integer('mark');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_unit_subjects');
    }
};
