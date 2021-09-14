<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveDealFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_deal_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pipedrive_id')->nullable();
            $table->string('key')->nullable();
            $table->string('name')->nullable();
            $table->integer('order_nr')->nullable();
            $table->string('field_type')->nullable();
            $table->boolean('json_column_flag')->default(0);
            $table->dateTime('add_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->boolean('active_flag')->nullable();
            $table->boolean('edit_flag')->nullable();
            $table->boolean('index_visible_flag')->nullable();
            $table->boolean('details_visible_flag')->nullable();
            $table->boolean('add_visible_flag')->nullable();
            $table->boolean('important_flag')->nullable();
            $table->boolean('bulk_edit_allowed')->nullable();
            $table->boolean('searchable_flag')->nullable();
            $table->boolean('filtering_allowed')->nullable();
            $table->boolean('sortable_flag')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_deal_fields');
    }
}
