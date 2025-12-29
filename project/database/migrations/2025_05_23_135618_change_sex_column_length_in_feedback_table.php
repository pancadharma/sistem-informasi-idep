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
        Schema::table('feedback', function (Blueprint $table) {
            // Ubah kolom 'sex' menjadi string dengan panjang yang cukup, misal 50
            // dan pastikan nullable jika memang bisa kosong (sesuai validasi Anda)
            $table->string('sex', 50)->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('feedback', function (Blueprint $table) {
            // Jika Anda tahu tipe dan panjang sebelumnya dan ingin bisa rollback penuh:
            // $table->string('sex', PANJANG_LAMA)->nullable()->change(); 
            // Atau jika lebih kompleks, Anda mungkin hanya membiarkannya
            // Untuk amannya, Anda bisa mencoba mengembalikan ke VARCHAR(255) default jika tidak yakin
            // Namun, fokus utamanya adalah method up() untuk perbaikan.
            // Untuk sementara, kita bisa asumsikan jika di rollback, kita ingin mengizinkan string yang lebih umum.
            $table->string('sex')->nullable()->change(); // Atau sesuaikan dengan definisi lama jika diketahui
        });
    }
};
