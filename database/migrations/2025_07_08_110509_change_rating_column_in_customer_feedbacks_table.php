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
        Schema::table('customer_feedback', function (Blueprint $table) {
            $table->tinyInteger('rating')->unsigned()->change(); // for values 1–5
        });
    }

    public function down()
    {
        Schema::table('customer_feedback', function (Blueprint $table) {
            $table->enum('rating', ['Good', 'Bad', 'Neutral'])->change(); // if you want to rollback
        });
    }

};
