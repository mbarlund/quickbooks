<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQbLedgerAccrualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qb_ledger_accruals', function ($table) {
            $table->increments('id');
            $table->integer('batch_num')->index();
            $table->string('doc_num')->index();
            $table->string('account', 50);
            $table->decimal('amount', 11, 2);
            $table->integer('user_id')->unsigned();
            $table->string('trns_type', 50);
            $table->string('name')->nullable()->default(null);
            $table->string('class')->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->string('memo')->nullable()->default(null);

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
        Schema::drop('qb_ledger_accruals');
    }

}
