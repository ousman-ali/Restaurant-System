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
        Schema::create('inhouse_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_no')->unique();
            $table->unsignedBigInteger('order_by');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Who created it
            $table->tinyInteger('status')->default(0); // 0: pending, 1: accepted, 2: purchased
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inhouse_orders');
    }
};
