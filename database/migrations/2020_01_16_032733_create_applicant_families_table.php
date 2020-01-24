<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantFamiliesTable extends Migration
{
    public function up()
    {
        Schema::create('applicant_families', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->string('relationship', 20);
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('occupation', 60)->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('living');
            $table->string('contact_no', 30)->nullable();
            $table->boolean('emergency_contact')->default(0);

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicant_families');
    }
}
