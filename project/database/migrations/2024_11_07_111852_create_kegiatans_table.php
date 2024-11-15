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
            $table->dateTime('tanggalselesai')->nullable();
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('cascade');
            $table->double('long')->nullable();
            $table->double('lat')->nullable();
            $table->foreignId('kategorilokasikegiatan_id')->constrained('mkategorilokasikegiatan')->onDelete('cascade');
            $table->string('tempat', 500)->nullable();
            $table->longText('deskripsi')->nullable();
            $table->longText('tujuan')->nullable();
            $table->longText('yangterlibat')->nullable();
            $table->string('pelatih', 500)->nullable();
            $table->longText('informasilain')->nullable();
            $table->string('luaslahan', 50)->nullable();
            $table->foreignId('jenisbantuan_id')->nullable()->constrained('mjenisbantuan')->onDelete('cascade');
            $table->foreignId('satuan_id')->nullable()->constrained('msatuan')->onDelete('cascade');
            $table->longText('tindaklanjut')->nullable();
            $table->longText('tantangan')->nullable();
            $table->longText('rekomendasi')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status', 50)->default('draft')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatan');
        Schema::enableForeignKeyConstraints();
    }
};
