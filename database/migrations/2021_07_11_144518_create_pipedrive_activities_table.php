<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipedriveActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('pipedrive_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pipedrive_id')->nullable();
            $table->string('master_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('done')->nullable();
            $table->string('type')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('add_time')->nullable();
            $table->string('marked_as_done_time')->nullable();
            $table->integer('notification_language_id')->nullable();
            $table->string('subject', 255)->nullable();
            $table->integer('org_id')->nullable();
            $table->integer('person_id')->nullable();
            $table->integer('deal_id')->nullable();
            $table->string('lead_id')->nullable();
            $table->string('lead_title')->nullable();
            $table->string('active_flag')->nullable();
            $table->datetime('update_time')->nullable();
            $table->integer('update_user_id')->nullable();
            $table->integer('created_by_user_id')->nullable();
            $table->string('series')->nullable();
            $table->string('org_name')->nullable();
            $table->string('person_name')->nullable();
            $table->string('deal_title', 255)->nullable();
            $table->string('owner_name')->nullable();
            $table->string('person_dropbox_bcc')->nullable();
            $table->string('deal_dropbox_bcc')->nullable();
            $table->integer('assigned_to_user_id')->nullable();
            $table->string('type_name')->nullable();
            $table->string('file')->nullable();
            $table->string('post_type')->default('last_activity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pipedrive_activities');
    }
}
