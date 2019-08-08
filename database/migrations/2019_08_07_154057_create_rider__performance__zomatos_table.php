<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiderPerformanceZomatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider__performance__zomatos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('date')->nullable();
            $table->string('feid')->nullable();
            $table->string('trips')->nullable();
            $table->string('adt')->nullable();
            $table->string('average_pickup_time')->nullable();
            $table->string('average_drop_time')->nullable();
            $table->string('loged_in_during_shift_time')->nullable();
            $table->string('total_loged_in_hours')->nullable();
            $table->string('cod_orders')->nullable();
            $table->string('cod_amount')->nullable();
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
        Schema::dropIfExists('rider__performance__zomatos');
    }
}
