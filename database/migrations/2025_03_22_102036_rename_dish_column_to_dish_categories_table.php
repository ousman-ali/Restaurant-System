<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::table('dish_categories', function (Blueprint $table) {
    //         $table->renameColumn('dish', 'name');
    //         $table->boolean('status')->default(true)->change();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('dish_categories', function (Blueprint $table) {
    //         $table->renameColumn('name', 'dish');
    //     });
    // }

    public function up(): void
    {
        // Use raw SQL to rename the column
        DB::statement('ALTER TABLE dish_categories CHANGE dish name VARCHAR(255)');

        // Then change status to default true
        Schema::table('dish_categories', function (Blueprint $table) {
            $table->boolean('status')->default(true)->change();
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE dish_categories CHANGE name dish VARCHAR(255)');
    }

};
