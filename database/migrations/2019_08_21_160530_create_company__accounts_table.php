<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company__accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('source')->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('id_charge_id')->nullable();
            $table->integer('bike_expense_id')->nullable();
            $table->integer('fine_id')->nullable();
            $table->integer('salik_id')->nullable();
            $table->integer('salary_id')->nullable();
            $table->integer('sim_transaction_id')->nullable();
            $table->integer('income_id')->nullable();
            $table->integer('investment_id')->nullable();
            $table->integer('mobile_installment_id')->nullable();
            $table->integer('rider_advance_id')->nullable();
            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('setting')->nullable();
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
        Schema::dropIfExists('company__accounts');
    }
}
