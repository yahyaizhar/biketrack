<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->nullable();
            $table->text('item_desc')->nullable();
            $table->string('item_rate')->nullable();
            $table->string('item_qty')->nullable();
            $table->string('item_amount')->nullable();
            $table->string('deductable')->nullable();
            $table->string('tax_method_id')->nullable();
            $table->string('taxable_amount')->nullable();
            $table->string('subtotal')->nullable();
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
        Schema::dropIfExists('invoice_items');
    }
}
