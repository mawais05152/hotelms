<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::table('stock_items', function (Blueprint $table) {
            $table->enum('item_type', ['product', 'asset', 'mess'])->change();
             $table->string('name')->nullable()->after('item_type');
             $table->string('unit')->nullable()->after('price');
             $table->decimal('total_cost', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('stock_items', function (Blueprint $table) {
        $table->dropColumn('name');
        $table->dropColumn('unit');
        $table->dropColumn('total_cost');
        $table->enum('item_type', ['product', 'asset' ,'mess' ])->change();
        });
    }

};
