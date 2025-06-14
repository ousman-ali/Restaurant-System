<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_no');
            $table->integer('table_id');
            $table->integer('served_by');
            $table->integer('kitchen_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('status')->default(0);
            $table->double('payment')->nullable();
            $table->double('vat')->default(0);
            $table->double('change_amount')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
