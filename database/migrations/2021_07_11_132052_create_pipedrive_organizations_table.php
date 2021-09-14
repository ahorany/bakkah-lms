<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pipedrive_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('open_deals_count')->nullable();
            $table->integer('related_open_deals_count')->nullable();
            $table->integer('closed_deals_count')->nullable();
            $table->integer('related_closed_deals_count')->nullable();
            $table->dateTime('add_time')->nullable();
            $table->dateTime('update_time')->nullable();
            $table->integer('visible_to')->nullable();
            $table->dateTime('next_activity_date')->nullable();
            $table->dateTime('next_activity_time')->nullable();
            $table->integer('next_activity_id')->nullable();
            $table->integer('last_activity_id')->nullable();
            $table->dateTime('last_activity_date')->nullable();
            $table->string('label')->nullable();
            $table->string('address')->nullable();
            $table->string('address_subpremise')->nullable();
            $table->string('address_street_number')->nullable();
            $table->string('address_route')->nullable();
            $table->string('address_sublocality')->nullable();
            $table->string('address_locality')->nullable();
            $table->string('address_admin_area_level_1')->nullable();
            $table->string('address_admin_area_level_2')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_formatted_address')->nullable();
            $table->string('cc_email')->nullable();
            $table->string('owner_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_organizations');
    }
}
