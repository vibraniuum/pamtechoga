<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaFuelPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_fuel_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->float('petrol');
            $table->float('diesel')->nullable();
            $table->float('premium')->nullable();
            $table->text('logo')->nullable();
            $table->json('meta')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pamtechoga_fuel_prices');
    }
}
