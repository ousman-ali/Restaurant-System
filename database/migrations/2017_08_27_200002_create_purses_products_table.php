<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePursesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purses_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purse_id');
            $table->integer('product_id');
            $table->double('quantity');
            $table->double('unit_price');
            $table->double('child_unit_price');
            $table->double('gross_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purses_products');
    }
}
