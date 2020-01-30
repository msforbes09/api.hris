<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('applicant_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('message_id');
            $table->timestamp('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_messages');
    }
}
