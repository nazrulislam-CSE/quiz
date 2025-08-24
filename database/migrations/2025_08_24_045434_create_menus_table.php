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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->nullable();;
            $table->string('slug', 25)->nullable();;
            $table->string('type', '25')->nullable();
            $table->string('location', '55')->nullable();
            $table->string('menu_source', '15')->comment('Category, Page, Custom link')->nullable();
            $table->string('source_id', '75')->nullable();
            $table->tinyInteger('top_header')->nullable();
            $table->tinyInteger('main_header')->nullable();
            $table->tinyInteger('footer')->nullable();
            $table->unsignedTinyInteger('position')->default(1)->comment('1=>Top Position, 2=>Middle Position, 3=>Bootom Position')->nullable();
            $table->integer('is_default')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=>Active, 0=>Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
