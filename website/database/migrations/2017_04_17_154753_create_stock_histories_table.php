<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('timestamp');
//            $table->string('time');
//            $table->float('low');
//            $table->float('high');
//            $table->float('open');
//            $table->float('close');
//            $table->integer('volume');
            $table->float('average');

            $table->integer('stock_id')->unsigned();

            //Establish foreign keys
            $table->foreign('stock_id')->references('id')->on('stocks');

//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_histories');
    }
}
