<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('dishes', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable(); // Ensuring type matches dish_categories.id
            $table->foreign('category_id')->references('id')->on('dish_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropForeign('category_id');
        });
    }
};
