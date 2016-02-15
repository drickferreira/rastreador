<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->string('name');
            $table->string('cnpj', 18);
            $table->string('insc', 20);
            $table->string('phone1', 15);
            $table->string('phone2', 15);
            $table->string('email', 50);
            $table->string('address', 100);
            $table->string('number', 10);
            $table->string('comp', 100);
            $table->string('quarter', 100);
            $table->string('city', 100);
            $table->string('state', 2);
            $table->string('country', 50);
            $table->integer('postalcode');
            $table->softDeletes();
            $table->timestamps();
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
        Schema::drop('companies');
    }

}
