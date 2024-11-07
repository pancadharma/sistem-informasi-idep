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
        Schema::create('trkegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programoutcomeoutputactivity_id')->constrained('trprogramoutcomeoutputactivity')->onDelete('cascade');
            $table->string('kode', 50);
            $table->string('nama', 500);
            $table->dateTime('tanggalmulai');
            $table->dateTime('tanggalselesai');
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('cascade');
            $table->decimal('long', 9, 6);
            $table->decimal('lat', 9, 6);
            $table->foreignId('kategorilokasikegiatan_id')->constrained('mkategorilokasikegiatan')->onDelete('cascade');
            $table->string('tempat', 200);
            $table->string('deskripsi', 500)->nullable();
            $table->string('tujuan', 500)->nullable();
            $table->string('yangterlibat', 500)->nullable();
            $table->string('pelatih', 500)->nullable();
            $table->string('informasilain', 500)->nullable();
            $table->string('luaslahan', 50)->nullable();
            $table->foreignId('jenisbantuan_id')->nullable()->constrained('mjenisbantuan')->onDelete('cascade');
            $table->foreignId('satuan_id')->nullable()->constrained('msatuan')->onDelete('cascade');
            $table->string('tindaklanjut', 500)->nullable();
            $table->string('tantangan', 500)->nullable();
            $table->string('rekomendasi', 500)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trkegiatan');
    }
};
