<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RiderReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rider_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('rider_id')->unsigned()->index()->nullable();
            $table->string('online_hours')->nullable();
            $table->string('no_of_trips')->nullable();
            $table->string('started_location')->nullable();
            $table->string('ended_location')->nullable();
            $table->string('mileage')->nullable();
            $table->string('no_of_hours')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('active_status')->default("A");
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
        Schema::dropIfExists('rider_reports');
    }
}
