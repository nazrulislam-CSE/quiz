<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();

            /* ============================================================
               BASIC INFO
            ============================================================ */
            $table->string('book_name');
            $table->string('slug')->unique();          // auto-generated from book_name
            $table->string('author_name');
            $table->string('book_photo')->nullable();  // storage path
            $table->integer('total_page')->nullable(); // page count

            /* ============================================================
               PRICING
            ============================================================ */
            $table->decimal('price', 10, 2)->default(0);         // original price
            $table->decimal('discount', 5, 2)->default(0);       // discount percentage (0-100)
            $table->decimal('sell_price', 10, 2)->default(0);    // final price after discount
            $table->decimal('commission', 5, 2)->default(0);     // commission % for referral

            /* ============================================================
               INVENTORY
            ============================================================ */
            $table->integer('quantity')->default(0);   // stock quantity

            /* ============================================================
               DESCRIPTION
            ============================================================ */
            $table->text('description')->nullable();

            /* ============================================================
               FLAGS & ANALYTICS
            ============================================================ */
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);

            $table->timestamps();

            // Indexes for filtering & performance
            $table->index('status');
            $table->index('is_featured');
            $table->index('author_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};