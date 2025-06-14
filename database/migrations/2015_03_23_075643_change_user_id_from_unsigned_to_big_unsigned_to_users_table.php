<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('users', function (Blueprint $table) {
            // Drop the existing primary key
            $table->dropPrimary();

            // Change 'id' to unsignedBigInteger
            $table->unsignedBigInteger('id')->change();
        });

        // Add auto-increment primary key after column modification
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY;');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('users', function (Blueprint $table) {
            // Drop primary key
            $table->dropPrimary();

            // Revert 'id' back to unsignedInteger
            $table->integer('id')->unsigned()->change();
        });

        // Re-add auto-increment primary key
        DB::statement('ALTER TABLE users MODIFY id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY;');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
