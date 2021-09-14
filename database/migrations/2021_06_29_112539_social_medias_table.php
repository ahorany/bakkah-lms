<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SocialMediasTable extends Migration
{
    public function up()
    {
        Schema::create('social_medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description');
            $table->text('constant_id');
            $table->integer('trashed_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        //
    }
}
