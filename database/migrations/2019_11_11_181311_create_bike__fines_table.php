<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBikeFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bike__fines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bike_id')->nullable();
            $table->integer('rider_id')->nullable();
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('given_date')->nullable();
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
        Schema::dropIfExists('bike__fines');
    }
}
