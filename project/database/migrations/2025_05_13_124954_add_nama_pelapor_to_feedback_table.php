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
            // Tambahkan baris ini
            $table->string('nama_pelapor')->nullable()->after('program_id'); // atau sesuaikan posisi dengan 'after()'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Tambahkan baris ini untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('nama_pelapor');
        });
    }
};
