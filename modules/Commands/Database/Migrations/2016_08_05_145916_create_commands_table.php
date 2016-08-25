<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
						$table->string('id_command');
						$table->tinyInteger('type');
            $table->uuid('device_id');
            $table->foreign('device_id')
                  ->references('id')->on('devices')
                  ->onDelete('cascade');
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
        Schema::drop('commands');
    }

}
