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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable()->after('user_id');
            // If there's a banks table with foreign key constraint
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->text('additional_note')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bank_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('additional_note');
        });
    }
};
