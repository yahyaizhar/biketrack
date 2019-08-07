<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('rider_id')->unsigned()->index()->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('rider_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('admin_id')->unsigned()->index()->nullable();
            $table->bigInteger('rider_id')->unsigned()->index()->nullable();
            $table->text('message')->nullable();
            // $table->foreign('admin_id')->references('id')->on('admins');
            // $table->foreign('rider_id')->references('id')->on('riders');
            $table->timestamps();
        });
        Schema::create('client_riders', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('client_id')->unsigned()->index()->nullable();
            $table->bigInteger('rider_id')->unsigned()->index()->nullable();
            $table->string('client_rider_id')->nullable();
            $table->boolean('status')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
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
        Schema::dropIfExists('rider_locations');
        Schema::dropIfExists('rider_messages');
        Schema::dropIfExists('client_riders');
    }
}
