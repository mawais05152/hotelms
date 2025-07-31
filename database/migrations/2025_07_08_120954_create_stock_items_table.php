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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['product', 'asset']);
            $table->unsignedBigInteger('product_id')->nullable(); 
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->integer('total_quantity');
            $table->integer('damaged_quantity');
            $table->integer('available_qty');
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
