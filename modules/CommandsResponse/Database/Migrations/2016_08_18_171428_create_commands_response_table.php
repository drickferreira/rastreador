<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandsResponseTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands_response', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
						$table->tinyInteger('fragment_number');
						$table->tinyInteger('fragment_count');
						$table->tinyInteger('attempt');
						$table->tinyInteger('sts_id');
						$table->string('desc')->nullable();
						$table->dateTime('timestamp');
						$table->uuid('command_id');
            $table->foreign('command_id')
                  ->references('id')->on('commands')
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
        Schema::drop('commands_response');
    }

}
