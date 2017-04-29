<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
//            $table->float('balance',20,2);
            $table->integer('user_id')->unsigned();

            $table->timestamps();
        });

        Schema::table('trade_accounts', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_accounts');
    }
}
