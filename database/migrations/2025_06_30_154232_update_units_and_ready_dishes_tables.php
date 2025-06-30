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
        
        Schema::table('units', function (Blueprint $table) {
            $table->string('unit')->nullable()->change();
            $table->string('child_unit')->nullable()->change();
        });

       
        Schema::table('ready_dishes', function (Blueprint $table) {
            $table->unsignedInteger('unit_id')->nullable()->after('supplier_id');
        });
    }

    public function down()
    {
        
        Schema::table('units', function (Blueprint $table) {
            $table->string('unit')->nullable(false)->change();
            $table->string('child_unit')->nullable(false)->change();
        });

        Schema::table('ready_dishes', function (Blueprint $table) {
            $table->dropColumn('unit_id');
        });
    }
};
