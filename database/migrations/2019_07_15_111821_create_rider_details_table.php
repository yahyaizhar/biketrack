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
            $table->string('salary')->nullable();
            $table->string('official_given_number')->nullable();
            $table->string('official_sim_given_date')->nullable();
            $table->integer('salik_amount')->default(50);
            $table->string('passport_collected')->nullable();
            $table->string('is_guarantee')->nullable();
            $table->integer('empoloyee_reference')->nullable();
            $table->string('other_passport_given')->nullable();
            $table->string('not_given')->nullable();
            $table->string('passport_document_image')->nullable();
            $table->string('agreement_image')->nullable(); 
            $table->string('passport_image')->nullable();
            $table->string('passport_image_back')->nullable();
            $table->string('passport_expiry')->nullable();
            $table->string('visa_image')->nullable();
            $table->string('visa_image_back')->nullable();
            $table->string('visa_expiry')->nullable();
            $table->string('emirate_image')->nullable();
            $table->string('emirate_image_back')->nullable();
            $table->string('emirate_id')->nullable();
            $table->string('licence_image')->nullable();
            $table->string('licence_image_back')->nullable();
            $table->string('licence_expiry')->nullable();
            $table->string('other_details')->nullable();
            $table->string('show_salaryslip')->nullable();
            $table->string('salaryslip_month')->nullable();
            $table->string('salaryslip_expiry')->nullable();
            $table->string('show_attendanceslip')->nullable();
            $table->string('others')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('rider_details');
    }
}
