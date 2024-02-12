<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePamtechogaCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pamtechoga_credit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id');
            $table->integer('payment_id');
            $table->double('amount', 16, 4);
            $table->double('before_balance', 16, 4);
            $table->enum('status', ['REFUND', 'CREDIT'])->default('CREDIT');
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
        Schema::dropIfExists('pamtechoga_credit_logs');
    }
}
