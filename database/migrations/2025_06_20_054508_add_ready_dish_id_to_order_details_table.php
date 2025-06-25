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
           $table->integer('dish_id')->nullable()->change();
           $table->integer('ready_dish_id')->nullable()->after('dish_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->integer('dish_id')->nullable(false)->change();
            $table->dropColumn('ready_dish_id');
        });
    }
};
