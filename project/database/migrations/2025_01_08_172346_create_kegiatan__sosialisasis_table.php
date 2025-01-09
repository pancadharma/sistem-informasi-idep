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
        Schema::create('trkegiatansosialisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('sosialisasiyangterlibat')->nullable();
            $table->text('sosialisasitemuan')->nullable();
            $table->boolean('sosialisasitambahan')->default(false);
            $table->text('sosialisasitambahan_ket')->nullable();
            $table->text('sosialisasikendala')->nullable();
            $table->text('sosialisasiisu')->nullable();
            $table->text('sosialisasipembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatansosialisasi');
        Schema::enableForeignKeyConstraints();
    }
};
