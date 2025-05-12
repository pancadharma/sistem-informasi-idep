<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrmealspreposttestTable extends Migration
{
    public function up()
    {
        Schema::create('trmealspreposttest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programoutcomeoutputactivity_id')->constrained('trprogramoutcomeoutputactivity')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('trainingname', 200);
            $table->dateTime('tanggalmulai');
            $table->dateTime('tanggalselesai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealspreposttest');
        Schema::enableForeignKeyConstraints();
    }
}
