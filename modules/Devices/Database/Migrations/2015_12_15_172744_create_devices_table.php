<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->string('name');
            $table->smallInteger('model');
            $table->integer('serial');
						$table->uuid('company_id')->nullable();
						$table->uuid('vehicle_id')->nullable();
						$table->text('description');
						$table->date('install_date');
            $table->softDeletes();
            $table->timestamps();
					  $table->primary('id');
						$table->foreign('company_id')
									->references('id')->on('companies')
									->onDelete('set null');
						$table->foreign('vehicle_id')
									->references('id')->on('vehicles')
									->onDelete('set null')
						$table->index('model');
						$table->unique('serial');
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
        Schema::drop('devices');
    }

}
