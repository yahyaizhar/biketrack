<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeZomatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_zomatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feid')->nullable();
            $table->string('p_id')->unique();
            $table->string('rider_id')->nullable();
            $table->string('import_id')->nullable();
            $table->string('log_in_hours_payable')->nullable();
            $table->string('trips_payable')->nullable();
            $table->integer('calculated_trips')->nullable();
            $table->integer('calculated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->string('total_to_be_paid_out')->nullable();
            $table->string('amount_for_login_hours')->nullable();
            $table->string('amount_to_be_paid_against_orders_completed')->nullable();
            $table->string('ncw_incentives')->nullable();
            $table->string('tips_payouts')->nullable();
            $table->string('denials_penalty')->nullable();
            $table->string('dc_deductions')->nullable();
            $table->string('mcdonalds_deductions')->nullable();
            $table->string('settlements')->nullable();
            $table->string('date')->nullable();
            $table->string('active_status')->default("A");
            $table->string('setting')->nullable();
            $table->string('off_day')->nullable();
            $table->integer('absents_count')->nullable();
            $table->integer('weekly_off')->nullable();
            $table->integer('extra_day')->nullable();
            $table->integer('working_days')->nullable();
            $table->integer('error')->nullable();
            $table->integer('approve_absents')->nullable();
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
        Schema::dropIfExists('income_zomatos');
    }
}
