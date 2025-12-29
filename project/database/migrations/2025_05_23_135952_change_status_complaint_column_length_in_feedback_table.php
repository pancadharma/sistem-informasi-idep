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
            // Ubah kolom 'status_complaint' menjadi string dengan panjang yang cukup, misal 50
            // dan pastikan nullable jika memang bisa kosong sebelum diisi default 'Baru'
            $table->string('status_complaint', 50)->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Opsional: Jika Anda ingin bisa rollback ke definisi sebelumnya
            // $table->string('status_complaint', PANJANG_LAMA_JIKA_DIKETAHUI)->nullable()->change();
            // Untuk amannya, kita bisa revert ke string umum jika tidak yakin definisi lamanya.
            $table->string('status_complaint')->nullable()->change();
        });
    }
};
