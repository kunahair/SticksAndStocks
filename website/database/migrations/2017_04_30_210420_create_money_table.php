<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('to')->unsigned();
            $table->integer('from')->unsigned();
            $table->float('amount');
            $table->float('taken')->default(0.00);

            $table->string('to_message');
            $table->string('from_message')->nullable();

            $table->boolean('to_read')->default(false);
            $table->boolean('from_read')->default(false);

            $table->integer('message_id')->unsigned()->nullable();

            $table->integer('timestamp');

            $table->timestamps();

            //Establish foreign keys
            $table->foreign('message_id')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money');
    }
}
