<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantEducationTable extends Migration
{
    public function up()
    {
        Schema::create('applicant_educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->string('level');
            $table->string('school');
            $table->year('year_from');
            $table->year('year_to');
            $table->string('details')->nullable();

            $table->foreign('applicant_id')->references('id')->on('applicants');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_education');
    }
}
