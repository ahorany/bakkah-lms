<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('session_id');
            $table->integer('user_id')->unsigned();
            $table->double('price')->default(0);
            $table->timestamps();
            $table->integer('trashed_status')->nullable();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');

            //Add session
            //Delete from my cart
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
