<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('last_name', 60);
            $table->string('first_name', 60);
            $table->string('middle_name', 60)->nullable();
            $table->string('nick_name', 60)->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('gender', 20);
            $table->string('height', 20)->nullable();
            $table->string('weight', 20)->nullable();
            $table->string('civil_status', 20)->nullable();
            $table->string('tax_code', 20)->nullable();
            $table->string('citizenship', 30)->nullable();
            $table->string('religion', 60)->nullable();
            $table->string('contact_no', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('crn', 25)->nullable();
            $table->string('sss', 25)->nullable();
            $table->string('tin', 25)->nullable();
            $table->string('philhealth', 25)->nullable();
            $table->string('pagibig', 25)->nullable();
            $table->string('pagibig_tracking', 25)->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE applicants ADD FULLTEXT fulltext_index (first_name, middle_name, last_name)');
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
