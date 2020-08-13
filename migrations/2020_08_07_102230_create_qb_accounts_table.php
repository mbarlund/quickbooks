<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQbAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qb_accounts', function ($table) {
            $table->increments('id');
            $table->string('accountable_type');
            $table->integer('accountable_id')->unsigned();
            $table->string('account');
            $table->string('name');
            $table->string('class');

            $table->timestamps();

            $table->index(['accountable_type', 'accountable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('qb_accounts');
    }

}
