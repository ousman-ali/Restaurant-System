<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cooked_products', function (Blueprint $table) {
            $table->integer('order_id')->nullable()->change();
            $table->unsignedBigInteger('supplier_order_id')->nullable()->after('order_id');
            $table->unsignedBigInteger('inhouse_order_id')->nullable()->after('supplier_order_id');
        });
    }

    public function down()
    {
        Schema::table('cooked_products', function (Blueprint $table) {
            $table->integer('order_id')->nullable(false)->change();
            $table->dropColumn(['supplier_order_id', 'inhouse_order_id']);
        });
    }
};
