<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RiderOnlineTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rider_online_times', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('rider_id')->unsigned()->index()->nullable();
            $table->datetime('online_time')->nullable();
            $table->datetime('offline_time')->nullable();
            $table->string('total_hours')->nullable();
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
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
        //
    }
}
