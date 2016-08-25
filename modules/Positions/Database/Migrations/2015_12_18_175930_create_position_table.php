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
            $table->integer('serial');
            $table->smallInteger('model');
            $table->string('ip', 25);
            $table->integer('memory_index')->unsigned();
            $table->tinyInteger('transmission_reason')->unsigned();
            $table->dateTime('date');
            $table->double('latitude', 18, 14);
            $table->double('longitude', 18, 14);
            $table->decimal('speed', 6, 2);
            $table->mediumInteger('hodometer');
            $table->decimal('power_supply', 4, 2);
            $table->tinyInteger('temperature');
            $table->boolean('ignition');
            $table->boolean('panic');
            $table->boolean('battery_charging');
            $table->boolean('battery_failure');
            $table->uuid('device_id');
            $table->softDeletes();
            $table->primary('id');
            $table->foreign('device_id')
                  ->references('id')->on('devices')
                  ->onDelete('cascade');
            $table->foreign('vehicle_id')
                  ->references('id')->on('vehicles')
                  ->onDelete('cascade');
						$table->index('model');
						$table->index('serial');
						$table->index(['model','serial']);
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
