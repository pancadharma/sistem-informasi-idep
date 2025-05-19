<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrmealspreposttestpesertaTable extends Migration
{
    public function up()
    {
        Schema::create('trmealspreposttestpeserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preposttest_id')->constrained('trmealspreposttest')->onDelete('cascade');
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('cascade');
            $table->string('nama', 200);
            $table->string('jeniskelamin', 50);
            $table->string('notelp', 20)->nullable();
            $table->integer('prescore')->nullable();
            $table->boolean('filedbytraineepre')->default(false);
            $table->integer('postscore')->nullable();
            $table->boolean('filedbytraineepost')->default(false);
            $table->integer('valuechange')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealspreposttestpeserta');
        Schema::enableForeignKeyConstraints();
    }
}