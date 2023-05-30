<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaDepotOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_depot_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('depot_id');
            $table->enum('status', ['PENDING', 'PROCESSING', 'LOADED', 'UNLOADED', 'CANCELED'])->default('PENDING');
            $table->float('volume');
            $table->float('unit_price');
            $table->float('trucking_expense')->default(0);
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
        Schema::dropIfExists('pamtechoga_depot_orders');
    }
}
