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
            $table->string('month')->nullable();
            $table->string('source')->nullable();
            $table->string('payment-status')->default("pending");
            $table->integer('rider_id')->nullable();
            $table->integer('salary_id')->nullable();
            $table->string('income_zomato_id')->nullable(); 
            $table->integer('advance_return_id')->nullable();
            $table->integer('id_charge_id')->nullable();
            $table->integer('wps_id')->nullable();
            $table->integer('fuel_expense_id')->nullable();
            $table->integer('maintenance_id')->nullable();
            $table->integer('edirham_id')->nullable();
            $table->integer('company_expense_id')->nullable();
            $table->string('salik_id')->nullable();
            $table->integer('sim_transaction_id')->nullable();
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
