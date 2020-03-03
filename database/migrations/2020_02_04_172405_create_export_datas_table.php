<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('given_date')->nullable();
            $table->string('source')->nullable();
            $table->string('payment_status')->default("pending");
            $table->integer('rider_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->string('bill_id')->nullable();
            $table->string('bill_acc')->nullable();
            $table->string('status')->default("1");
            $table->string('active_status')->default("A");
            $table->timestamps();
        });
         //then set autoincrement to 100
        //after creating the table
        DB::update("ALTER TABLE export_datas AUTO_INCREMENT = 100;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_datas');
    }
}
