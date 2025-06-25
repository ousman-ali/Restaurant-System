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
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('dish_id')->nullable()->change();         // Make dish_id nullable
            $table->integer('ready_dish_id')->nullable()->after('dish_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('dish_id')->nullable(false)->change();    // Revert if needed
            $table->dropColumn('ready_dish_id');   
        });
    }
};
