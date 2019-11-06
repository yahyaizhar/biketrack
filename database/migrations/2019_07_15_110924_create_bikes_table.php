<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bikes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner')->nullable();
            $table->string('model')->nullable();
            $table->string('bike_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('rental_company')->nullable();
            $table->string('contract_start')->nullable();
            $table->string('contract_end')->nullable();
            $table->string('rent_amount')->nullable();
            $table->string('amount')->nullable();
            $table->string('bike_allowns')->nullable();
            $table->integer('rider_id')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('mulkiya_number')->nullable();
            $table->string('mulkiya_picture')->nullable();
            $table->string('mulkiya_expiry')->nullable();
            $table->string('mulkiya_picture_back')->nullable();
            $table->string('availability')->default('yes');
            $table->timestamps();
            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('is_given')->nullable();
            $table->string('others')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bikes');
    }
}
