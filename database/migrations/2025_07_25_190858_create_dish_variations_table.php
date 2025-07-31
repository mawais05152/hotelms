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
        Schema::create('dish_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mess_menu_id')->constrained('mess_menus')->onDelete('cascade')->nullable();
            $table->string('name'); // Half, Full
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_variations');
    }
};
