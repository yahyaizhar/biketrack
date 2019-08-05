<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewComersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_comers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('education')->nullable();
            $table->string('licence_issue_date')->nullable();
            $table->string('source_of_contact')->nullable();
            $table->string('experiance')->nullable();
            $table->string('experience_input')->nullable();
            $table->string('passport_status')->nullable();
            $table->string('passport_reason')->nullable();
            $table->string('kingriders_interview')->nullable();
            $table->string('interview')->nullable();
            $table->string('interview_status')->nullable();
            $table->string('interview_date')->nullable();
            $table->string('interview_By')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('why_rejected')->nullable();
            $table->string('overall_remarks')->nullable();
            $table->string('priority')->nullable();
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
        Schema::dropIfExists('new_comers');
    }
}
