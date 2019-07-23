<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('date_of_joining')->nullable();
            $table->string('official_given_number')->nullable();
            $table->string('official_sim_given_date')->nullable();
            $table->string('passport_image')->nullable();
            $table->string('passport_expiry')->nullable();
            $table->string('visa_image')->nullable();
            $table->string('visa_expiry')->nullable();
            $table->string('licence_image')->nullable();
            $table->string('licence_expiry')->nullable();
            $table->string('mulkiya_image')->nullable();
            $table->string('mulkiya_expiry')->nullable();
            $table->string('others')->nullable();
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
        Schema::dropIfExists('rider_details');
    }
}
