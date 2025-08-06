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
            $table->unsignedBigInteger('available_quantity')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mess_menus', function (Blueprint $table) {
            $table->dropColumn('available_quantity');
        });
    }
};
