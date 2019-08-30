<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip__details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('import_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('trip_date')->nullable();
            $table->string('trip_time')->nullable();
            $table->string('transaction_post_date')->nullable();
            $table->string('toll_gate')->nullable();
            $table->string('direction')->nullable();
            $table->string('tag_number')->nullable();
            $table->string('plate')->nullable();
            $table->string('amount_aed')->nullable(); 
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
        Schema::dropIfExists('trip__details');
    }
}
