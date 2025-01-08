<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trkegiatankonsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('konsultasilembaga')->nullable();
            $table->text('konsultasikomponen')->nullable();
            $table->text('konsultasiyangdilakukan')->nullable();
            $table->text('konsultasihasil')->nullable();
            $table->text('konsultasipotensipendapatan')->nullable();
            $table->text('konsultasirencana')->nullable();
            $table->text('konsultasikendala')->nullable();
            $table->text('konsultasiisu')->nullable();
            $table->text('konsultasipembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatankonsultasi');
        Schema::enableForeignKeyConstraints();
    }
};
