<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveProductAttachedsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_product_attacheds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pipedrive_id')->nullable();
            $table->string('deal_id')->nullable();
            $table->string('order_nr')->nullable();
            $table->string('product_id')->nullable();
            $table->string('product_variation_id')->nullable();
            $table->double('item_price')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->integer('duration')->default(0);
            $table->string('duration_unit')->nullable();
            $table->integer('sum_no_discount')->default(0);
            $table->double('sum')->default(0);
            $table->string('currency')->nullable();
            $table->string('enabled_flag')->nullable();
            $table->datetime('add_time')->nullable();
            $table->datetime('last_edit')->nullable();
            $table->string('comments')->nullable();
            $table->string('active_flag')->nullable();
            $table->string('tax')->nullable();
            $table->string('name')->nullable();
            $table->string('sum_formatted')->nullable();
            $table->string('quantity_formatted')->nullable();
            $table->string('quantity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_product_attacheds');
    }
}
