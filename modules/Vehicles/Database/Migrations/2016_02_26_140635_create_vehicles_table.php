<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->string('plate', 8);
            $table->string('brand', 20);
            $table->string('model', 50);
            $table->tinyInteger('year');
            $table->string('color', 20);
            $table->boolean('active');
            $table->boolean('panic');
            $table->uuid('account_id');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->foreign('account_id')
                  ->references('id')->on('accounts')
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
        Schema::drop('vehicles');
    }

}
