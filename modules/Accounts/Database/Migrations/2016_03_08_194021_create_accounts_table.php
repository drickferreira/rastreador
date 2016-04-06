<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function(Blueprint $table)
        {
            $table->uuid('id');
            $table->string('name');
            $table->string('cpf_cnpj', 18);
            $table->string('phone1', 15);
            $table->string('phone2', 15);
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('company_id');
            $table->primary('id');
            $table->foreign('company_id')
                  ->references('id')->on('companies')
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
        Schema::drop('accounts');
    }

}
