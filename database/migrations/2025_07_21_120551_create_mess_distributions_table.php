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
        Schema::create('mess_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mess_meal_id')->constrained('mess_menus')->onDelete('cascade');
            $table->string('person_name');
            $table->string('quantity_given');
            $table->string('remarks')->nullable();
            $table->dateTime('delivered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_distributions');
    }
};
