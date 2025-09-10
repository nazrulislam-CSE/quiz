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
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id')->nullable();  // The original user
            $table->unsignedBigInteger('to_user_id')->nullable();    // Referrer at this level

            $table->integer('level')->default(0);                    // 0 = Direct, 1 = First Gen, etc.
            $table->dateTime('date')->nullable();                    // Referral timestamp
            $table->tinyInteger('status')->default(1);               // 1 = Success, 0 = Failed, etc.

            $table->decimal('commission', 16, 2)->default(0);        // Commission amount
            $table->decimal('total_amount', 16, 2)->default(0);      // Original transaction amount

            $table->timestamps();

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};
