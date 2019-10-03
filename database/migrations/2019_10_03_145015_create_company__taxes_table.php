<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company__taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('z_import_id')->nullable();
            $table->string('type')->nullable();
            $table->string('month')->nullable();
            $table->string('total_to_be_paid_out')->nullable();
            $table->string('log_in_hours_payable')->nullable();
            $table->string('trips_payable')->nullable();
            $table->string('amount_for_login_hours')->nullable();
            $table->string('amount_to_be_paid_against_orders_completed')->nullable();
            $table->string('total_to_be_paid_out_with_tax')->nullable();
            $table->string('taxable_amount')->nullable();
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
        Schema::dropIfExists('company__taxes');
    }
}
