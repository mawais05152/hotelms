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
        Schema::create('daily_order_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('total_orders');
            $table->integer('total_sales_amount');
            $table->string('pending_orders_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_order_reports');
    }
};
