<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default("normal");
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('logo')->nullable();
            $table->string('Active_status')->default("A"); 
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('logo')->nullable();
            $table->string('password');
            $table->boolean('status')->nullable();
            $table->string('active_status')->default("A");
            $table->timestamp('email_verified_at')->nullable();
            $table->text('setting')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('riders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('area_id')->nullable();
            $table->integer('kingriders_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('password');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('break_start_time')->nullable();
            $table->string('break_end_time')->nullable();
            $table->string('inactive_month')->nullable();
            $table->string('inactive_reason')->nullable();
            $table->text('spell_time')->nullable();
            $table->boolean('online')->nullable();
            $table->boolean('status')->nullable();
            $table->string('active_status')->default("A");
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('riders');
    }
}
