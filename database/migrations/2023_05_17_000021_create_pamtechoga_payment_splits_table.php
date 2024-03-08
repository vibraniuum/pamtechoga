<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaPaymentSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_payment_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id');
            $table->integer('order_id')->nullable();
            $table->integer('bf_organization_id')->nullable();
            $table->double('amount', 16, 4);
            $table->enum('status', ['SPLIT', 'REFUNDED'])->default('SPLIT');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pamtechoga_reviews');
    }
}
