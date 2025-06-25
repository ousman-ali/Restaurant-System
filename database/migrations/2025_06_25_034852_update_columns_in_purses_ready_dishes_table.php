<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE purses_ready_dishes CHANGE quantity pending_quantity INT');
    
        Schema::table('purses_ready_dishes', function (Blueprint $table) {
            $table->integer('ready_quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE purses_ready_dishes CHANGE pending_quantity quantity INT');
    
        Schema::table('purses_ready_dishes', function (Blueprint $table) {
            $table->dropColumn('ready_quantity');
        });
    }
};
