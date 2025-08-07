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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('phone')->nullable();
            $table->double('visa_amount')->default(0.00)->comment('Total Visa Amount')->ullable();
            $table->double('withdraw_amount')->default(0.00)->comment('Total Withdraw Amount')->nullable();
            $table->double('v_total_amount')->default(0.00)->comment('Total Voucher Amount')->nullable();
            $table->double('voucher_amount')->default(0.00)->comment('Total Due Amount')->nullable();
            $table->double('v_advance_payment')->default(0.00)->comment('Total Advance Amount')->nullable();
            $table->string('image')->default('user.png')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('established_year')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('about')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('present_address')->nullable();
            $table->string('parmanent_address')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('gender')->nullable();
            $table->string('edu_qualification')->nullable();
            $table->string('experience')->nullable();
            $table->string('staff_id')->nullable();
            $table->string('staff_type')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('office_zone')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('discharge_date')->nullable();
            $table->string('machine_id')->nullable();
            $table->string('description')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('show_password');
            $table->string('password');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
