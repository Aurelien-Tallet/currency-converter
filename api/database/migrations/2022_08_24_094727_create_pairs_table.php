<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedDouble('rate');
            // $table->unsignedBigInteger('currency_id_from')->nullable();
            // $table->unsignedBigInteger('currency_id_to')->nullable();


            $table->foreignId('currency_id_from')->references('id')->on('currencies');
            $table->foreignId('currency_id_to')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pairs');
    }
};
