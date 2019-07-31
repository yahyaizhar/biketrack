<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim__transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->integer('sim_id')->nullable();
            $table->string('month_year')->nullable();
            $table->string('bill_amount')->nullable();
            $table->string('extra_usage_amount')->nullable();
            $table->string('extra_usage_payment_status')->nullable();
            $table->string('bill_status')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sim__transactions');
    }
}
