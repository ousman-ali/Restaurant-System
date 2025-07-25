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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('rest_type')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_to_cafe')->nullable();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('rest_type');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_to_cafe');
        });
    }
};
