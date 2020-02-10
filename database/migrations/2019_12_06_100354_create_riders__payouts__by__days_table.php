<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRidersPayoutsByDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders__payouts__by__days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('feid')->nullable();
            $table->string('zomato_income_id')->nullable();
            $table->string('date')->nullable();
            $table->string('login_hours')->nullable();
            $table->string('trips')->nullable();
            $table->string('payout_for_login_hours')->nullable();
            $table->string('payout_for_trips')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('off_days_status')->nullable();
            $table->string('absent_status')->nullable();
            $table->integer('absent_fine_id')->nullable();
            $table->string('absent_detail_status')->default("0");
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
        Schema::dropIfExists('riders__payouts__by__days');
    }
}
