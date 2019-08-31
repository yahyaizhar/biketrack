<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company__expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->string('amount')->nullable();
            $table->string('month')->nullable();
            $table->string('status')->nullable();
            $table->string('active_status')->default("A");
            $table->string('setting')->nullable();
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
        Schema::dropIfExists('company__expenses');
    }
}
