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
        // Fix inhouse_orders table
        Schema::table('inhouse_orders', function (Blueprint $table) {
            $table->dropColumn(['admin_id', 'purchased_at']);
            $table->unsignedBigInteger('baker_id')->nullable()->after('order_by');
            $table->timestamp('cook_start_at')->nullable()->after('status');
            $table->timestamp('cook_complete_at')->nullable()->after('cook_start_at');
        });

        // Fix supplier_orders table
        Schema::table('supplier_orders', function (Blueprint $table) {
            $table->dropColumn(['baker_id', 'cook_start_at', 'cook_complete_at']);
            $table->unsignedBigInteger('admin_id')->nullable()->after('order_by');
            $table->timestamp('purchased_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        // Rollback changes
        Schema::table('inhouse_orders', function (Blueprint $table) {
            $table->dropColumn(['baker_id', 'cook_start_at', 'cook_complete_at']);
            $table->unsignedBigInteger('admin_id')->nullable()->after('order_by');
            $table->timestamp('purchased_at')->nullable()->after('status');
        });

        Schema::table('supplier_orders', function (Blueprint $table) {
            $table->dropColumn(['admin_id', 'purchased_at']);
            $table->unsignedBigInteger('baker_id')->nullable()->after('order_by');
            $table->timestamp('cook_start_at')->nullable()->after('status');
            $table->timestamp('cook_complete_at')->nullable()->after('cook_start_at');
        });
    }
};
