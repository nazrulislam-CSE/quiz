<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eproduct_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('eproduct_id')->constrained('eproducts')->cascadeOnDelete();

            $table->string('order_no')->unique();

            /* ============================================================
               BUYER INFO
            ============================================================ */
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone')->nullable();

            /* ============================================================
               SHIPPING INFO
            ============================================================ */
            $table->string('shipping_name')->nullable();      // Name
            $table->string('shipping_phone')->nullable();     // Mobile number
            $table->text('shipping_address')->nullable();     // Full address
            $table->string('shipping_city')->nullable();      // City / District
            $table->string('shipping_postcode')->nullable();  // Postcode (optional)

            /* ============================================================
               DELIVERY TYPE & SHIPPING CHARGE
            ============================================================ */
            $table->enum('delivery_type', ['inside_dhaka', 'outside_dhaka', 'self_receive'])
                  ->default('inside_dhaka');
            $table->decimal('shipping_charge', 8, 2)->default(0);

            /* ============================================================
               PRICING
            ============================================================ */
            $table->decimal('price', 10, 2)->default(0);        // product original price
            $table->decimal('discount', 5, 2)->default(0);      // discount %
            $table->decimal('product_total', 10, 2)->default(0); // product price after discount
            $table->decimal('paid_amount', 10, 2)->default(0);  // product_total + shipping_charge

            /* ============================================================
               PAYMENT INFO
            ============================================================ */
            $table->string('payment_gateway')->default('eps');
            $table->string('merchant_transaction_id')->nullable();
            $table->string('eps_transaction_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->json('gateway_response')->nullable();

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('payment_status');
            $table->index('merchant_transaction_id');
            $table->index('delivery_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eproduct_orders');
    }
};