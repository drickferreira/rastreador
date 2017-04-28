<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informations', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->tinyInteger('transmission_reason')->unsigned();
            $table->mediumInteger('hodometer');
            $table->decimal('power_supply', 4, 2);
            $table->tinyInteger('temperature');
            $table->boolean('panic');
            $table->boolean('battery_charging');
            $table->boolean('battery_failure');
						$table->boolean('gps_signal')->default(true);
						$table->boolean('gps_antenna_failure')->default(true);
            $table->uuid('position_id');
						$table->integer('lifetime')->unsigned()->default(0);
            $table->primary('id');
            $table->foreign('position_id')
                  ->references('id')->on('positions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('informations');
    }

}
