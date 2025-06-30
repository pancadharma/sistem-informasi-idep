<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenchmarkTable extends Migration
{
    public function up()
    {
        Schema::create('benchmark', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('jeniskegiatan_id')->constrained('mjeniskegiatan')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('kelurahan')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->onDelete('cascade');
            $table->foreignId('provinsi_id')->constrained('provinsi')->onDelete('cascade');
            $table->date('tanggalimplementasi');
            $table->string('handler', 500);
            $table->foreignId('usercompiler_id')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->string('catatanevaluasi', 500)->nullable();
            $table->string('area', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('benchmark');
    }
}
