<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveProductsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pipedrive_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('code')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('unit')->nullable();
            $table->double('tax')->default(0);
            $table->integer('category')->nullable();
            $table->string('active_flag')->nullable();
            $table->string('selectable')->nullable();
            $table->string('first_char')->nullable();
            $table->integer('visible_to')->nullable();
            $table->datetime('add_time')->nullable();
            $table->datetime('update_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_products');
    }
}
