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
        Schema::create('mess_finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mess_meal_id')->constrained('mess_menus')->onDelete('cascade');
            $table->decimal('total_cost', 10, 2);
            $table->decimal('price_per_person', 10, 2);
            $table->integer('persons_served');
            $table->decimal('total_income', 10, 2);
            $table->string('profit_or_loss');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_finances');
    }
};
