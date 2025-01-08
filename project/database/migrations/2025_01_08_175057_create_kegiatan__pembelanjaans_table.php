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
        Schema::create('trkegiatanpembelanjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('pembelanjaandetailbarang')->nullable();
            $table->dateTime('pembelanjaanmulai')->nullable();
            $table->dateTime('pembelanjaanselesai')->nullable();
            $table->dateTime('pembelanjaandistribusimulai')->nullable();
            $table->dateTime('pembelanjaandistribusiselesai')->nullable();
            $table->boolean('pembelanjaanterdistribusi')->default(false);
            $table->boolean('pembelanjaanakandistribusi')->default(false);
            $table->text('pembelanjaanakandistribusi_ket')->nullable();
            $table->text('pembelanjaankendala')->nullable();
            $table->text('pembelanjaanisu')->nullable();
            $table->text('pembelanjaanpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanpembelanjaan');
        Schema::enableForeignKeyConstraints();
    }
};
