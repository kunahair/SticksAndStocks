<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('to')->usnsigned();
            $table->integer('from')->unsinged();
            $table->integer('timestamp')->unsigned();
            $table->boolean('pending');
            $table->boolean('accept_view')->default(false);

            //Establish foreign keys
//            $table->foreign('to')->references('id')->on('users');
//
//            //Establish foreign keys
//            $table->foreign('from')->references('id')->on('users');

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
        Schema::dropIfExists('friends');
    }
}
