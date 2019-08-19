<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_installments', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('mobile_id')->unsigned()->index()->nullable();
            $table->string('installment_month')->nullable();
            $table->integer('installment_amount')->nullable();
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
        Schema::dropIfExists('mobile_installments');
    }
}
