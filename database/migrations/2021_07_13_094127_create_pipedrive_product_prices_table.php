<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveProductPricesTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_product_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pipedrive_id')->nullable();
            $table->string('pipedrive_product_id')->nullable();
            $table->double('price')->default(0);
            $table->string('currency')->nullable();
            $table->double('cost')->default(0);
            $table->string('overhead_cost')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_product_prices');
    }
}
