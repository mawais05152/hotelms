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
         Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('variation_id')->nullable()->after('asset_type');
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    public function down()
    {
         Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['variation_id']);
            $table->dropColumn('variation_id');
        });

    }

};
