<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRiderOnlineTimesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('rider_online_times', function(Blueprint $table) {
            $table->renameColumn('total_hours', 'total_minutes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('rider_online_times', function(Blueprint $table) {
            $table->renameColumn('total_minutes', 'total_hours');
        });
    }
}
