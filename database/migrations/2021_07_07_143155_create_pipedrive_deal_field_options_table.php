<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveDealFieldOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_deal_field_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('master_id')->nullable();
            $table->string('pipedrive_id')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_deal_field_options');
    }
}
