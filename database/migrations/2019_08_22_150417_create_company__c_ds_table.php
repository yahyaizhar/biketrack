<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCDsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company__c_ds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_id')->nullable();
            $table->string('type_db')->nullable();
            $table->string('type_cr')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('advance_description')->nullable();
            $table->string('advance_deducted_by')->nullable();
            $table->string('advance_notes')->nullable();
            $table->string('salary_total')->nullable();
            $table->string('salary_gross')->nullable();
            $table->string('salary_recieved')->nullable();
            $table->string('salary_remaining')->nullable();
            $table->string('type')->nullable(); 
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
        Schema::dropIfExists('company__c_ds');
    }
}
