<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile__transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mobile_id')->nullable();
            $table->integer('sale_price')->nullable();
            $table->integer('amount_received')->nullable();
            $table->integer('remaining_amount')->nullable();
            $table->integer('per_month_installment_amount')->nullable();
            $table->string('bill_status')->nullable();
            $table->string('month')->nullable();
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
        Schema::dropIfExists('mobile__transactions');
    }
}
