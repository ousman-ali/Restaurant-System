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
        Schema::create('ready_dishes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->double('price');
            $table->integer('stock')->default(0);
            $table->enum('source_type', ['inhouse', 'supplier']);
            $table->integer('supplier_id')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ready_dishes');
    }
};
