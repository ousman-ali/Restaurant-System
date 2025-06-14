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
        Schema::create('web_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section_id')->unique();
            $table->longText('content')->nullable();
            $table->longText('styles')->nullable();
            $table->boolean('in_navbar')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_page_sections');
    }
};
