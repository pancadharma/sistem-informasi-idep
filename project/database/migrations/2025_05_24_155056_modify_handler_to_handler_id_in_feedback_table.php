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
             // Jika kolom 'handler' sudah ada dan bertipe string, Anda mungkin perlu menghapusnya dulu
    // atau merenamenya jika ada data yang ingin diselamatkan/migrasi manual.
    // Untuk contoh ini, kita asumsikan bisa langsung ubah atau tambah baru jika handler belum ada.
    // Jika 'handler' sudah ada dan string: $table->dropColumn('handler');

    $table->unsignedBigInteger('handler_id')->nullable()->after('kontak_penerima'); // Sesuaikan posisi
    $table->foreign('handler_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            //
        });
    }
};
