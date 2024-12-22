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
        Schema::table('trprogramoutcomeoutputactivity', function (Blueprint $table) {
            $table->string('kode', 50)->after('id')->nullable(); // Tambahkan kolom 'kode' setelah kolom 'id'
            $table->string('nama', 500)->after('kode')->nullable(); // Tambahkan kolom 'nama' setelah kolom 'kode'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trprogramoutcomeoutputactivity', function (Blueprint $table) {
            $table->dropColumn(['kode', 'nama']); // Hapus kolom jika rollback dilakukan
        });
    }
};
