<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandParametersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_parameters', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
						$table->string('parameter_id');
						$table->string('value');
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
        Schema::drop('command_parameters');
    }

}
