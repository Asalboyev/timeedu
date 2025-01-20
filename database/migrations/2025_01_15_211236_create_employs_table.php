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
        Schema::create('employs', function (Blueprint $table) {
            $table->id();
            $table->text('first_name');
            $table->text('last_name');
            $table->text('surname')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->text('special')->nullable();
            $table->text('photo')->nullable();
            $table->string('phone')->nullable();
            $table->text('started_work')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employs');
    }
};
