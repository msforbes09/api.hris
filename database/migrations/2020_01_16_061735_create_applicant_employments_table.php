<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantEmploymentsTable extends Migration
{
    public function up()
    {
        Schema::create('applicant_employments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->string('company');
            $table->string('address');
            $table->year('date_from');
            $table->year('date_to');
            $table->string('position');
            $table->decimal('salary');
            $table->string('leaving_reason');

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_employments');
    }
}
