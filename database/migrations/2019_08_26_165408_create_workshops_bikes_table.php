<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopsBikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops_bikes', function (Blueprint $table) {
            $table->integer('workshop_id')->unsigned()->nullable();
            $table->foreign('workshop_id')->references('id')
                    ->on('workshops')->onDelete('cascade');

            $table->integer('bike_id')->unsigned()->nullable();
            $table->foreign('bike_id')->references('id')
                    ->on('bikes')->onDelete('cascade');

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
        Schema::dropIfExists('workshops_bikes');
    }
}
