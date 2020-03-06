<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('client_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50);
            $table->unsignedBigInteger('client_id');
            $table->string('name', 100);
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_branches');
    }
}
