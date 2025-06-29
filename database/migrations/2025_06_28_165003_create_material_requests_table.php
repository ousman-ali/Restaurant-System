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
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['recipe_product', 'ready_dish']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->double('current_quantity')->nullable();
            $table->double('threshold')->nullable();
            $table->double('requested_quantity')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
