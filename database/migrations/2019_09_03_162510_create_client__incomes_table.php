<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client__incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('acc_id')->nullable();
            $table->string('month')->nullable();
            $table->string('given_date')->nullable();
            $table->string('perday_hours')->nullable();
            $table->string('working_days')->nullable();
            $table->string('total_hours')->nullable();
            $table->string('extra_hours')->nullable();
            $table->string('captain_id')->nullable();
            $table->string('trips')->nullable();
            $table->string('captain_tips')->nullable();
            $table->string('item_bought')->nullable();
            $table->string('item_qty')->nullable();
            $table->string('week_start')->nullable();
            $table->string('week_end')->nullable();
            $table->string('total')->nullable();
            $table->string('total_payout')->nullable();
            $table->string('income_type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('client__incomes');
    }
}
