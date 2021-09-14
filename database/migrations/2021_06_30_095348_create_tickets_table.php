<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->integer('issue');
            $table->integer('priority');
            $table->integer('status');
            $table->text('description');
            $table->integer('company');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('tracked_by');
            $table->boolean('updated_status')->default(0)->nullable();
            $table->timestamp('tracked_at')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
