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
        Schema::table('dish_infos', function (Blueprint $table) {
            $table->integer('ready_dish_id')->nullable()->after('dish_id');
            $table->integer('dish_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dish_infos', function (Blueprint $table) {
            $table->dropColumn('ready_dish_id');
            $table->integer('dish_id')->nullable(false)->change(); // revert to NOT NULL if needed
        });
    }
};
