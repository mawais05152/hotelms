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
        Schema::create('damaged_items', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['product', 'asset']);
            $table->unsignedBigInteger('stock_item_id')->nullable();
            // $table->foreignId('variation_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('size')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->date('damage_date');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_items');
    }
};
