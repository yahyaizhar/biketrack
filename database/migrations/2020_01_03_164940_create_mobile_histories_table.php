<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->integer('mobile_id')->nullable();
            $table->string('mobile_assign_date')->nullable();
            $table->string('mobile_unassign_date')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('installment_amount')->nullable();
            $table->string('installment_starting_date')->nullable();
            $table->string('installment_ending_date')->nullable();
            $table->string('active_status')->default("A");
            $table->string('status')->default("1");
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
        Schema::dropIfExists('mobile_histories');
    }
}
