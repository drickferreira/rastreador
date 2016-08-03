<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataFields extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('positions', function(Blueprint $table)
        {
					$table->dropColumn('ip');
					$table->boolean('gps_signal')->default(true);
					$table->boolean('gps_antenna_failure')->default(true);
					$table->integer('lifetime')->unsigned()->default(0);
					$table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function(Blueprint $table)
        {
					 $table->dropColumn('gps_signal');
					 $table->dropColumn('gps_antenna_failure');
					 $table->dropColumn('lifetime');

        });
    }

}
