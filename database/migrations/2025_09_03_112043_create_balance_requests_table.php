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
        Schema::create('balance_requests', function (Blueprint $table) {
            $table->id();

            // Manual 
            $table->unsignedBigInteger('user_id');
            $table->string('method')->nullable();
            $table->string('from_account')->nullable(); 
            $table->decimal('amount', 10, 2);
            $table->string('trx_id')->nullable();
            $table->string('screenshot')->nullable(); 

            // EPS
            $table->string('payment_gateway')->nullable();
            $table->string('merchant_transaction_id')->nullable()->unique();
            $table->string('eps_transaction_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_gateway');
            $table->index('paid_at');

            $table->index('trx_id');
            $table->index('eps_transaction_id');
            $table->index('gateway_transaction_id');

            // Frequently used combined queries
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'payment_status']);
            $table->index(['payment_gateway', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_requests');
    }
};
