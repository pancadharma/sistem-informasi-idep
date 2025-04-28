<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_program_id_to_feedback_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ===========================================
// UBAH BAGIAN INI: Gunakan Nama Class Eksplisit
// ===========================================
class AddProgramIdToFeedbackTable extends Migration // Nama class harus sesuai konvensi dari nama file
{
// ===========================================
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Sesuaikan nama kolom 'program_id' jika perlu
            // Sesuaikan nama tabel 'programs' dan kolom 'id' jika perlu
            // after('nama_kolom_sebelumnya') bersifat opsional untuk posisi kolom
            $table->foreignId('program_id')->nullable()->constrained('trprogram')->after('id');
            // atau jika tidak pakai constraint otomatis:
            // $table->unsignedBigInteger('program_id')->nullable()->after('id');
            // $table->foreign('program_id')->references('id')->on('programs');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Urutan drop constraint penting jika Anda mendefinisikannya secara manual
            // $table->dropForeign(['program_id']);
            $table->dropConstrainedForeignId('program_id'); // Lebih mudah jika pakai constrained
            // $table->dropColumn('program_id');
        });
    }
// ===========================================
// Tambahkan kurung kurawal penutup untuk class
// ===========================================
};
// ===========================================
// Hapus baris 'return new class extends Migration' yang lama
// ===========================================