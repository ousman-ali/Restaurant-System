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
        Schema::create('produced_ready_dishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ready_dish_id');
            $table->double('ready_quantity');
            $table->double('pending_quantity');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produced_ready_dishes');
    }
};
