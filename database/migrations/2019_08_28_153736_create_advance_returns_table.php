<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('month')->nullable();
            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('setting')->nullable();
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
        Schema::dropIfExists('advance_returns');
    }
}
