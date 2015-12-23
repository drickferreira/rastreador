<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->string('ip', 25);
            $table->smallInteger('memory_index')->unsigned();
            $table->tinyInteger('transmission_reason')->unsigned();
            $table->dateTime('date');
            $table->double('latitude', 10, 6);
            $table->double('longitude', 10, 6);
            $table->char('direction', 1);
            $table->decimal('speed', 3, 2);
            $table->mediumInteger('hodometer');
            $table->decimal('power_supply', 2, 2);
            $table->tinyInteger('temperature');
            $table->boolean('ignition');
            $table->boolean('panic');
            $table->boolean('battery_charging');
            $table->boolean('battery_failure');
            $table->uuid('device_id');
            $table->softDeletes();
            $table->primary('id');
        });
        
        Schema::table('positions', function($table)
        {
            $table->foreign('device_id')
                  ->references('id')->on('devices')
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
        Schema::drop('positions');
    }

}
