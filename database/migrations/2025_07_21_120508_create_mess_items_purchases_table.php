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
        Schema::create('mess_items_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('ingredient_name');
            $table->decimal('quantity', 8, 2);
            $table->string('unit');
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->string('purchased_by');
            $table->date('purchased_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_items_purchases');
    }
};
