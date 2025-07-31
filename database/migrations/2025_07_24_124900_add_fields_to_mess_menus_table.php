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
         Schema::table('mess_menus', function (Blueprint $table) {
            $table->string('ingredient_name')->nullable()->after('quantity_made');
            $table->decimal('quantity_used', 8, 2)->nullable()->after('ingredient_name');
            $table->string('unit')->nullable()->after('quantity_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mess_menus', function (Blueprint $table) {
           $table->dropColumn(['ingredient_name', 'quantity_used', 'unit']);
        });
    }
};
