<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_order_id')->nullable(); // if the payment is by a customer, attach order id
            $table->integer('depot_order_id')->nullable(); // if payment is to a depot, attach depot order id
            $table->integer('organization_id'); // if payment is by an organization, attach organization ID
            $table->integer('user_id'); // track who created the record
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELED'])->default('PENDING');
            $table->enum('type', ['DOWN PAYMENT', 'DEBT', 'DEPOT', 'OTHER'])->default('OTHER');
            $table->double('amount', 16, 2);
            $table->dateTime('payment_date');
            $table->text('reference_photo')->nullable();
            $table->text('reference_description')->nullable();
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
        Schema::dropIfExists('pamtechoga_payments');
    }
}
