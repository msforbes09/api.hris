<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_sms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sms_id');
            $table->unsignedBigInteger('message_id');
            $table->timestamp('status');
            $table->unsignedBigInteger('mt_id');

            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->foreign('sms_id')->references('id')->on('sms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_sms');
    }
}
