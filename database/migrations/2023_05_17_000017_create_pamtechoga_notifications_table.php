<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('message');
            $table->enum('type', ['news', 'fuelPrice', 'payment', 'order', 'announcement', 'general'])->default('general');
            $table->integer('type_id')->nullable();
            $table->integer('organization_id')->nullable();
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
        Schema::dropIfExists('pamtechoga_notifications');
    }
}
