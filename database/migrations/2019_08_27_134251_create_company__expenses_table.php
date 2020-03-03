<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company__expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('given_date')->nullable();
            $table->string('month')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->string('paid_by')->nullable();
            $table->string('bill_picture')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bill_id')->nullable();
            $table->string('bill_acc')->nullable();
            $table->string('active_status')->default("A");
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
        Schema::dropIfExists('company__expenses');
    }
}
