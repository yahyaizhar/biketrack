<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('emp_allowances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('month');
            $table->string('given_date');
            $table->string('amount');
            $table->string('active_status')->default('A');
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
        Schema::dropIfExists('emp_allowances');
    }
}
