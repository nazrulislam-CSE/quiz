<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_orders', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            // Order info
            $table->string('order_no')->unique();

            // Pricing (snapshot — course price future-এ change হলেও order safe থাকবে)
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);

            // Payment info
            $table->string('payment_gateway')->default('eps');
            $table->string('merchant_transaction_id')->nullable()->unique();
            $table->string('eps_transaction_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_method')->nullable(); // bKash / Nagad / Card
            $table->json('gateway_response')->nullable();

            // Status
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])
                  ->default('pending');

            $table->enum('status', ['pending', 'completed', 'cancelled'])
                  ->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_orders');
    }
};