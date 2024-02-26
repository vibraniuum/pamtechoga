<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaDepotPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_depot_pickups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('depot_order_id');
            $table->integer('driver_id');
            $table->enum('status', ['PENDING', 'PROCESSING', 'LOADED', 'UNLOADED', 'CANCELED'])->default('PENDING');
            $table->float('volume_assigned');
            $table->float('volume_balance');
            $table->dateTime('loaded_datetime');
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
        Schema::dropIfExists('pamtechoga_depot_pickups');
    }
}
