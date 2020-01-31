<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('sms_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('sms_id');
            $table->unsignedBigInteger('mt_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('description', 191)->nullable();
            $table->timestamps();

            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->foreign('sms_id')->references('id')->on('sms');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_statuses');
    }
}
