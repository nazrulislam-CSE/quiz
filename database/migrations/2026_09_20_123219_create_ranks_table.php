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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('rank_name');
            $table->decimal('rank_deposit', 15, 2)->default(0);

            // Cash reward amount
            $table->decimal('rank_reward', 15, 2)->default(0);

            // Reward description / alternative reward
            $table->text('reward_text')->nullable();

            // 1 = Active, 0 = Inactive
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
