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
        Schema::create('mess_menus', function (Blueprint $table) {
            $table->id();
            $table->string('meal_name');
            $table->date('date');
            $table->string('cooked_by');
            $table->integer('cooked_for_persons');
            $table->string('quantity_made');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_menus');
    }
};
