<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->bigInteger('imei_1')->nullable();
            $table->bigInteger('imei_2')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('vat_paid')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('amount_received')->nullable();
            $table->string('remaining_amount')->nullable();
            $table->integer('purchased_invoice_id')->nullable();
            $table->string('seller_id')->nullable();
            $table->string('purchasing_date')->nullable();
            $table->string('invoice_picture')->nullable();
            $table->integer('status')->default('1');
            $table->string('payment_status')->default('pending');
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
        Schema::dropIfExists('mobiles');
    }
}
