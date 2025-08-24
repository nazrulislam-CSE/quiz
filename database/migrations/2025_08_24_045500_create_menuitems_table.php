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
        Schema::create('menuitems', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('page_id')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('title_hidden')->nullable();
            $table->string('menu_type', '10')->nullable()->comment('Horizontal,Vertical');
            $table->string('url')->nullable();
            $table->string('sourch')->nullable();
            $table->string('location')->nullable();
            $table->string('target')->default("_self");
            $table->string('sub_title')->nullable();
            $table->string('menu_width')->nullable();
            $table->unsignedTinyInteger('position')->default(1)->comment('1=>Top Position, 2=>Middle Position, 3=>Bootom Position')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=>Active, 0=>Inactive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menuitems');
    }
};
