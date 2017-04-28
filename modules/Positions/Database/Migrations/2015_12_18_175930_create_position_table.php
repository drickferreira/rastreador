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
            $table->dateTime('date');
            $table->double('latitude', 18, 14);
            $table->double('longitude', 18, 14);
            $table->decimal('speed', 6, 2);
            $table->boolean('ignition');
            $table->uuid('device_id');
            $table->primary('id');
            $table->foreign('device_id')
                  ->references('id')->on('devices')
                  ->onDelete('cascade');
            $table->foreign('vehicle_id')
                  ->references('id')->on('vehicles')
                  ->onDelete('cascade');
						$table->index(['model','serial']);
						$table->index(['model','serial', 'date']);
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
