<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->date('signing_date');
            $table->date('expired_date');
            $table->boolean('is_active');
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
        Schema::dropIfExists('agreements');
    }
}
