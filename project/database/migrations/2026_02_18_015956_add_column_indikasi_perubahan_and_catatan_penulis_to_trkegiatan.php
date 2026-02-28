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
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->longText('indikasi_perubahan')->nullable()->after('deskripsiyangdikaji');
            $table->longText('catatan_penulis')->nullable()->after('indikasi_perubahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->dropColumn('indikasi_perubahan');
            $table->dropColumn('catatan_penulis');
        });
    }
};
