<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrmealsqbTable extends Migration
{
    public function up()
    {
        Schema::create('trmealsqb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('jeniskegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->foreignId('programoutcomeoutputactivity_id')->constrained('trprogramoutcomeoutputactivity')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('kelurahan')->onDelete('cascade');
            $table->date('tanggalimplementasi');
            $table->foreignId('userhandler_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('usercompiler_id')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->string('catatanevaluasi', 500)->nullable();
            $table->string('area', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trmealsqb');
    }
}
