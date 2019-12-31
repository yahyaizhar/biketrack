<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('route_name')->nullable(); 
            $table->text('route_description')->nullable(); 
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
        Schema::dropIfExists('web_routes');
    }
}
