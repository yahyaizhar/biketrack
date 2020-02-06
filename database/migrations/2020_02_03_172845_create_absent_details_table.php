<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absent_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->text('absent_reason')->nullable();
            $table->string('absent_date')->nullable();
            $table->string('email_sent')->nullable();
            $table->string('document_image')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('status')->default("1");
            $table->string('settings')->nullable();
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
        Schema::dropIfExists('absent_details');
    }
}
