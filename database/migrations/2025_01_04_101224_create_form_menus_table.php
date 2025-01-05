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
        Schema::create('form_menus', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
            $table->integer('order')->nullable();
            $table->integer('position')->default(0);
            $table->string('title');
            $table->unsignedBigInteger('dinamik_menu_id'); // Foreign key to `dinamik_mens`
            $table->timestamps();
            $table->softDeletes(); // Soft delete qo'shish

            $table->foreign('dinamik_menu_id')->references('id')->on('dinamik_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_menus');
    }
};
