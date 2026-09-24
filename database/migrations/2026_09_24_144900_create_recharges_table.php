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
        Schema::create('recharges', function (Blueprint $table) {

            $table->id();

            // কোন user recharge করেছে
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // আমাদের generated transaction ID
            $table->string('transaction_id')
                ->unique();

            // মোবাইল নম্বর
            $table->string('number', 11);

            // Operator: GP, BL, RB, AT, TT
            $table->string('operator', 5);

            // Recharge amount
            $table->decimal('amount', 12, 2);

            // success / failed
            $table->enum('status', [
                'success',
                'failed',
            ])->default('success');

            // Provider-এর transaction ID থাকলে
            $table->string('provider_transaction_id')
                ->nullable();

            // Provider-এর সম্পূর্ণ response রাখার জন্য
            $table->json('response')
                ->nullable();

            $table->timestamps();

            // দ্রুত history search-এর জন্য
            $table->index([
                'user_id',
                'created_at'
            ]);

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharges');
    }
};
