<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('code');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_branch_id');
            $table->unsignedBigInteger('client_position_id');
            $table->date('interview_date')->nullable();
            $table->string('interview_stat');
            $table->string('interview_remarks', 191)->nullable();
            $table->date('exam_date')->nullable();
            $table->string('exam_stat');
            $table->string('exam_remarks', 191)->nullable();
            $table->string('medical_stat');
            $table->string('medical_remarks', 191)->nullable();
            $table->string('requirement_stat');
            $table->string('requirement_remarks', 191)->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
