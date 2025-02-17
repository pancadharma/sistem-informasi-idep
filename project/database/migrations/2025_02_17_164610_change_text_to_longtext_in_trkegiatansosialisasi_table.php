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
        Schema::table('trkegiatansosialisasi', function (Blueprint $table) {
            $table->longText('sosialisasiyangterlibat')->nullable()->change();
            $table->longText('sosialisasitemuan')->nullable()->change();
            $table->longText('sosialisasitambahan_ket')->nullable()->change();
            $table->longText('sosialisasikendala')->nullable()->change();
            $table->longText('sosialisasiisu')->nullable()->change();
            $table->longText('sosialisasipembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatansosialisasi', function (Blueprint $table) {
            $table->text('sosialisasiyangterlibat')->nullable()->change();
            $table->text('sosialisasitemuan')->nullable()->change();
            $table->text('sosialisasitambahan_ket')->nullable()->change();
            $table->text('sosialisasikendala')->nullable()->change();
            $table->text('sosialisasiisu')->nullable()->change();
            $table->text('sosialisasipembelajaran')->nullable()->change();
        });
    }
};