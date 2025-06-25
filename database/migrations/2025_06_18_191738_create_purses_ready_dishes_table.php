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
        Schema::create('purses_ready_dishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purse_id');
            $table->integer('ready_dish_id');
            $table->double('quantity');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purses_ready_dishes');
    }
};
