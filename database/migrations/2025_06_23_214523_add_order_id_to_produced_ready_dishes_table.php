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
        Schema::table('produced_ready_dishes', function (Blueprint $table) {
            $table->unsignedBigInteger('order_detail_id')->nullable()->after('ready_dish_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produced_ready_dishes', function (Blueprint $table) {
            //
        });
    }
};
