<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('booking_id');
        $table->unsignedBigInteger('order_by')->nullable();
        $table->unsignedBigInteger('delivered_by')->nullable();
        $table->string('order_type')->nullable();
        $table->integer('person');
        $table->date('date')->nullable();
        $table->time('time');
        // $table->time('time')->default(DB::raw('CURRENT_TIME'));
        $table->enum('status', ['Pending', 'Completed'])->default('Pending');
        $table->timestamps();

        $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
        $table->foreign('order_by')->references('id')->on('users')->onDelete('set null');
        $table->foreign('delivered_by')->references('id')->on('users')->onDelete('set null');
    });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
