<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('given_date')->nullable();
            $table->string('source')->nullable();
            $table->string('payment_status')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('active_status')->nullable();
            $table->integer('sim_transaction_id')->nullable();
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
        Schema::dropIfExists('employee_accounts');
    }
}
