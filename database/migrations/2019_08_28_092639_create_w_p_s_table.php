<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_p_s_s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name')->nullable();
            $table->integer('rider_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('payment_status')->nullable();
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
        Schema::dropIfExists('w_p_s_s');
    }
}
