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
            $table->integer('amount')->nullable();
            $table->string('month')->nullable();
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
