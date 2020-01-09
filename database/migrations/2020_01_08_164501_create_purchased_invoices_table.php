<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasedInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_purchase_id')->nullable();
            $table->string('invoice_amount')->nullable();
            $table->string('invoice_picture')->nullable();
            $table->string('purchasing_date')->nullable();
            $table->string('tex_amount')->nullable();
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
        Schema::dropIfExists('purchased_invoices');
    }
}
