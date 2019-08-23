<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdChargesTable extends Migration
{ 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('id_charges');
    }
}
