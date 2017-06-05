<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('timestamp')->unsigned();
            $table->integer('bought')->unsigned();
            $table->integer('sold')->unsigned();
            $table->float('price');
            $table->boolean('waiting')->unsigned();

            $table->integer('trade_account_id')->unsigned();
            $table->integer('stock_id')->unsigned();

            //Establish foreign keys
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('trade_account_id')->references('id')->on('trade_accounts');

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
        Schema::dropIfExists('transactions');
    }
}
