<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('master_id')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_options');
    }
}
