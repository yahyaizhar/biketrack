<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestNewComersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('guest_new_comers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('newcommer_image')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('national_id_card_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('education')->nullable();
            $table->string('license_check')->nullable();
            $table->string('license_number')->nullable();
            $table->string('licence_issue_date')->nullable();
            $table->string('license_image')->nullable();
            $table->string('experiance')->nullable();
            $table->string('passport_status')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_image')->nullable();
            $table->string('current_residence')->nullable();
            $table->string('current_residence_countries')->nullable();
            $table->string('source')->nullable();
            $table->text('overall_remarks')->nullable();
            $table->string('status_approval_message')->nullable();
            $table->string('approval_status')->default('pending');
            $table->string('interview_status')->nullable();
            $table->string('interview_status_message')->nullable();
            $table->string('interview_date')->nullable();
            $table->string('interview_by')->nullable();
            $table->string('active_status')->default("0");
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
        Schema::dropIfExists('guest_new_comers');
    }
}
