<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBikeAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bike__accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('source')->nullable();
            $table->integer('bike_rent_id')->nullable();
            $table->string('payment_status')->default("pending");
            $table->integer('bike_id')->nullable();
            $table->integer('maintenance_id')->nullable();
            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('setting')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('bike__accounts');
    }
}
