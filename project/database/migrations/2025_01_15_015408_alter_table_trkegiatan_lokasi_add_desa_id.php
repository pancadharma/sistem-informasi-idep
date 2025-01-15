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
        Schema::table('trkegiatan_lokasi', function (Blueprint $table) {
            $table->foreignId('desa_id')->nullable()->constrained('kelurahan')->onDelete('cascade');
        });

        Schema::table('trkegiatan_lokasi', function (Blueprint $table) {
            $table->foreignId('desa_id')->nullable()->change()->after('kegiatan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatan_lokasi', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn('desa_id');
        });
    }
};
