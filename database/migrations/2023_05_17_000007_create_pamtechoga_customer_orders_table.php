<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_customer_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->enum('status', ['PENDING', 'PROCESSING', 'DISPATCHED', 'DELIVERED', 'CANCELED'])->default('PENDING');
            $table->integer('organization_id');
            $table->integer('branch_id')->nullable();
            $table->double('volume', 16, 2);
            $table->float('unit_price');
            $table->integer('driver_id')->nullable();
            $table->boolean('made_down_payment')->default(false);
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
        Schema::dropIfExists('pamtechoga_customer_orders');
    }
}
