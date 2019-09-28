<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyInvestmentsTable extends Migration
{
    /** 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_investments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('investor_id')->nullable();
            $table->string('amount')->nullable();
            $table->text('notes')->nullable();
            $table->string('month')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('company_investments');
    }
}
