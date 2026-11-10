<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eproducts', function (Blueprint $table) {
            $table->id();

            /* ============================================================
               BASIC INFO
            ============================================================ */
            $table->string('product_name');
            $table->string('slug')->unique();                // auto-generated
            $table->string('product_photo')->nullable();     // storage path
            $table->string('brand_name')->nullable();
            $table->string('size')->nullable();              // e.g. "M, L, XL"
            $table->string('color')->nullable();             // e.g. "Red, Blue"

            /* ============================================================
               PRICING
            ============================================================ */
            $table->decimal('price', 10, 2)->default(0);           // original price
            $table->decimal('discount', 5, 2)->default(0);         // discount percentage (0–100)
            $table->decimal('customer_price', 10, 2)->default(0);  // final price after discount
            $table->decimal('commission', 10, 2)->default(0);      // commission amount (taka)

            /* ============================================================
               INVENTORY
            ============================================================ */
            $table->integer('quantity')->default(0);

            /* ============================================================
               DESCRIPTION
            ============================================================ */
            $table->text('description')->nullable();
            $table->string('warranty')->nullable();          // e.g. "1 year", "6 months"

            /* ============================================================
               FLAGS & ANALYTICS
            ============================================================ */
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);

            $table->timestamps();

            /* ============================================================
               INDEXES
            ============================================================ */
            $table->index('status');
            $table->index('is_featured');
            $table->index('brand_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eproducts');
    }
};