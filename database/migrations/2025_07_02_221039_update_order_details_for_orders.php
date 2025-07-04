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
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_order_id')->nullable()->after('order_id');
            $table->unsignedBigInteger('inhouse_order_id')->nullable()->after('supplier_order_id');
            $table->integer('order_id')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
            $table->double('net_price')->nullable()->change();
            $table->double('gross_price')->nullable()->change();

            $table->foreign('supplier_order_id')->references('id')->on('supplier_orders')->onDelete('set null');
            $table->foreign('inhouse_order_id')->references('id')->on('inhouse_orders')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
