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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->unsignedBigInteger('asset_id')->nullable();
            $table->string('invoice_no')->unique();
            $table->string('name')->unique();
            $table->string('asset_type');
            $table->integer('total_quantity');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('supplier_name');
            // $table->string('warehouse_name');
            $table->date('purchase_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
