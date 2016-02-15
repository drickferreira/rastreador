<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehiclesRelationship extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_vehicle', function(Blueprint $table)
        {
						$table->uuid('device_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
						$table->date('install_date');
						$table->date('remove_date');
						$table->text('description');
						$table->softDeletes();
            $table->foreign('device_id')
                  ->references('id')->on('devices');
            $table->foreign('vehicle_id')
                  ->references('id')->on('vehicles');
						$table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('device_vehicle');
    }

}
