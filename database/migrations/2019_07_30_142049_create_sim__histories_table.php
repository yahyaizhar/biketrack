<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim__histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sim_id')->nullable();
            $table->integer('rider_id')->nullable();
            $table->string('allowed_balance')->nullable();
            $table->string('given_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('sim__histories');
    }
}
