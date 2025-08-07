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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('address')->nullable();
            $table->double('v_total_amount')->default(0.00)->comment('Total Voucher Amount')->nullable();
            $table->double('voucher_amount')->default(0.00)->comment('Total Due Amount')->nullable();
            $table->double('v_advance_payment')->default(0.00)->comment('Total Advance Amount')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('show_password');
            $table->integer('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
