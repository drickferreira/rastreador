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
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id');
						$table->uuid('company_id');
						$table->foreign('company_id')
									->references('id')->on('companies');
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
        Schema::drop('devices');
    }

}
