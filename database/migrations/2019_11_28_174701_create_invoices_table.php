<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('invoice_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('invoice_total')->nullable();
            $table->string('invoice_subtotal')->nullable();
            $table->string('month')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('invoice_due')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('generated_by')->nullable();
            $table->string('tax_method_id')->nullable();
            $table->string('taxable_amount')->nullable();
            $table->string('taxable_subtotal')->nullable();
            $table->string('bank_id')->nullable();
            $table->string('amount_paid')->nullable();
            $table->string('due_balance')->nullable();
            $table->string('received_date')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('attachment')->nullable();
            $table->text('message_on_invoice')->nullable();
            $table->text('billing_address')->nullable();

            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->timestamps();
        });
        //then set autoincrement to 1000
        //after creating the table
        DB::update("ALTER TABLE invoices AUTO_INCREMENT = 1000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
