<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Method ini dijalankan ketika Anda menjalankan 'php artisan migrate'.
     */
    public function up(): void
    {
        // Membuat tabel baru bernama 'feedback'
        Schema::create('feedback', function (Blueprint $table) {
            // Kolom standar:
            $table->id(); // Primary Key auto-increment (BigInt)

            // Kolom dari $fillable:
            $table->string('program')->nullable();
            $table->date('tanggal_registrasi'); // Tipe date (dari $casts)
            $table->integer('umur')->nullable(); // Asumsi integer untuk umur
            $table->string('penerima')->nullable();
            $table->string('sort_of_complaint'); // Asumsi string, not nullable karena inti keluhan
            $table->string('age_group')->nullable();
            $table->string('position')->nullable();
            $table->date('tanggal_selesai')->nullable(); // Tipe date (dari $casts)
            $table->enum('sex', ['Laki-laki', 'Perempuan', 'Lainnya'])->nullable(); // Tipe enum berdasarkan konteks umum
            $table->string('kontak_penerima')->nullable();
            $table->string('handler')->nullable();
            $table->string('phone_number')->nullable(); // String untuk fleksibilitas format (+, -, spasi)
            $table->string('channels')->nullable();
            $table->string('position_handler')->nullable();
            $table->text('address')->nullable(); // Tipe text untuk alamat panjang
            $table->string('other_channel')->nullable();
            $table->string('kontak_handler')->nullable();
            $table->string('kategori_komplain')->nullable();
            $table->text('deskripsi'); // Tipe text untuk deskripsi panjang, not nullable
            $table->enum('status_complaint', ['Baru', 'Diproses', 'Selesai', 'Ditolak'])->default('Baru'); // Enum dengan default 'Baru'

            // Kolom standar:
            $table->timestamps(); // Membuat kolom created_at dan updated_at (tipe timestamp)
        });
    }

    /**
     * Reverse the migrations.
     * Method ini dijalankan ketika Anda menjalankan 'php artisan migrate:rollback'.
     */
    public function down(): void
    {
        // Menghapus tabel 'feedback' jika migrasi di-rollback
        Schema::dropIfExists('feedback');
    }
};