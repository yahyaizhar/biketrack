<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->text('feed')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('given_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('bill_changes');
    }
}
