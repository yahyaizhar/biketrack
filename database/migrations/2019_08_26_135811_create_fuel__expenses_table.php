<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel__expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bike_id')->nullable();
            $table->integer('rider_id')->nullable();
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
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
        Schema::dropIfExists('fuel__expenses');
    }
}
