<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiderSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('total_salary')->nullable();
            $table->string('gross_salary')->nullable();
            $table->string('recieved_salary')->nullable();
            $table->string('remaining_salary')->nullable();
            $table->string('month')->nullable();
            $table->string('paid_by')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('status')->nullable();
            $table->string('settings')->nullable();
            $table->string('active_status')->default("A");
            $table->string('salary_slip_image')->nullable();
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
        Schema::dropIfExists('rider_salaries');
    }
}
