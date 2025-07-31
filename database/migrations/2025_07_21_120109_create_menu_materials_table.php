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
        Schema::create('menu_materials', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('mess_meal_id')->constrained('mess_menus')->onDelete('cascade');
            $table->string('ingredient_name');
            $table->decimal('quantity_used', 8, 2);
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_materials');
    }
};
