<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plate_number');
            $table->float('volume_capacity');
            $table->json('meta')->nullable();
            $table->text('chart')->nullable();
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
        Schema::dropIfExists('pamtechoga_trucks');
    }
}
