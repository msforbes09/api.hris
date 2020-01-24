<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('client_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10);
            $table->unsignedBigInteger('client_id');
            $table->string('name', 100);
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_positions');
    }
}
