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

            /* ============================================================
               RELATIONS
            ============================================================ */

            // কে কিনছে (Buyer — যিনি টাকা দিচ্ছেন)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // কে Access পাবে (Student / Receiver)
            // Self purchase: student_id = user_id
            // Gift purchase: student_id = receiver user (registered user)
            // Guest gift: student_id = NULL, student_name/email/phone ব্যবহার হবে
            $table->foreignId('student_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->cascadeOnDelete();

            /* ============================================================
               ORDER INFO
            ============================================================ */

            $table->string('order_no')->unique();

            // Gift purchase flag
            $table->boolean('is_gift')->default(false);

            /* ============================================================
               BUYER SNAPSHOT (যিনি টাকা দিচ্ছেন)
               Future-এ user update হলে order snapshot safe থাকবে
            ============================================================ */

            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone')->nullable();

            /* ============================================================
               STUDENT SNAPSHOT (যিনি Access পাবে)
            ============================================================ */

            $table->string('student_name')->nullable();
            $table->string('student_email')->nullable();
            $table->string('student_phone')->nullable();

            /* ============================================================
               PRICING SNAPSHOT
               Future-এ course price change হলেও order safe থাকবে
            ============================================================ */

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);

            /* ============================================================
               PAYMENT INFO
            ============================================================ */

            $table->string('payment_gateway')->default('eps');
            $table->string('merchant_transaction_id')->nullable()->unique();
            $table->string('eps_transaction_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_method')->nullable(); // bKash / Nagad / Card
            $table->json('gateway_response')->nullable();

            /* ============================================================
               STATUS
            ============================================================ */

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])
                  ->default('pending');

            $table->enum('status', ['pending', 'completed', 'cancelled'])
                  ->default('pending');

            $table->timestamp('paid_at')->nullable();

            /* ============================================================
               TIMESTAMPS
            ============================================================ */

            $table->timestamps();

            /* ============================================================
               INDEXES — for faster query
            ============================================================ */

            $table->index(['user_id', 'payment_status']);
            $table->index(['student_id', 'course_id', 'payment_status']); // duplicate check
            $table->index(['course_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_orders');
    }
};