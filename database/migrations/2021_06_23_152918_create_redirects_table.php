<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectsTable extends Migration
{

    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('source_url');
            $table->text('destination_url');
            $table->text('constant_id');
            $table->integer('trashed_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirects');
    }
}
