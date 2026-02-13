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
            // $table->enum('item_type', ['product', 'asset', 'mess'])->change()->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->enum('item_type', ['product', 'asset']);
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->integer('damaged_quantity')->nullable();
            $table->integer('available_qty')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->nullable();
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
