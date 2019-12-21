<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->text('updated_old')->nullable();
            $table->text('updated_new')->nullable();
            $table->string('causer_id')->nullable();
            $table->string('causer_type')->nullable();   
            $table->string('setting')->nullable();  
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
        Schema::dropIfExists('log_activities');
    }
}
