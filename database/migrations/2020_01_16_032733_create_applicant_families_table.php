<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_families', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->string('relationship');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('living');

            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_families');
    }
}
