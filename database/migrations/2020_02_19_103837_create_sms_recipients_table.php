<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsRecipientsTable extends Migration
{
    public function up()
    {
        Schema::create('sms_recipients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('sms_id');
            $table->string('status')->default('pending');
            $table->string('mid', 30)->nullable();
            $table->timestamps();

            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->foreign('sms_id')->references('id')->on('sms');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_recipients');
    }
}
