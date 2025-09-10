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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_id')->nullable();     // Who triggered the transaction (e.g., buyer)
            $table->unsignedBigInteger('user_id')->nullable();     // Who received the commission
            $table->unsignedBigInteger('from_user')->nullable();   // Redundant? Could be same as from_id

            $table->string('out')->nullable();                     // Category/type (e.g., 'referral')
            $table->string('status')->nullable();                  // 'success', 'pending', etc.
            $table->string('purpose')->nullable();                 // Description (e.g., 'Direct Referral Commission')

            $table->decimal('amount', 16, 2)->nullable();           // Commission amount

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
