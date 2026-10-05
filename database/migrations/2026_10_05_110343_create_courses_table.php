<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('course_type_id')
                  ->constrained('course_types')
                  ->cascadeOnDelete();

            $table->foreignId('course_class_id')
                  ->constrained('course_classes')
                  ->cascadeOnDelete();

            $table->foreignId('course_category_id')
                  ->constrained('course_categories')
                  ->cascadeOnDelete();

            // Basic info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);

            // Extra info
            $table->integer('total_class')->nullable();
            $table->string('duration')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Flags
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);

            // Analytics
            $table->unsignedBigInteger('view_count')->default(0);

            $table->timestamps();

            // Index for faster query
            $table->index(['course_type_id', 'course_class_id', 'course_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};