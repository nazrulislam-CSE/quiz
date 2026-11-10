<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_book_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained('ebooks')->cascadeOnDelete();

            $table->string('order_no')->unique();

            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);

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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_orders');
    }
};