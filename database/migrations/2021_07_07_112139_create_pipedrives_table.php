<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedrivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipedrives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pipedrive_id')->nullable();
            $table->string('title', 500)->nullable();
            $table->double('value')->default(0);
            $table->double('weighted_value')->default(0);
            $table->integer('stage_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('probability')->nullable();
            $table->integer('visible_to')->nullable();
            $table->integer('pipeline_id')->nullable();
            $table->integer('products_count')->nullable();
            $table->integer('followers_count')->nullable();
            $table->integer('label')->nullable();
            $table->integer('stage_order_nr')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('deleted')->default(0);
            $table->datetime('add_time')->nullable();
            $table->datetime('update_time')->nullable();
            $table->datetime('stage_change_time')->nullable();
            $table->integer('files_count')->nullable();
            $table->string('renewal_type')->nullable();
            $table->string('org_name', 500)->nullable();
            $table->string('weighted_value_currency')->nullable();
            $table->datetime('rotten_time')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('cc_email')->nullable();
            $table->boolean('org_hidden')->default(0);
            $table->boolean('person_hidden')->default(0);
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
        Schema::dropIfExists('pipedrives');
    }
}
