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
        Schema::table('trkegiatankonsultasi', function (Blueprint $table) {
            $table->longText('konsultasilembaga')->nullable()->change();
            $table->longText('konsultasikomponen')->nullable()->change();
            $table->longText('konsultasiyangdilakukan')->nullable()->change();
            $table->longText('konsultasihasil')->nullable()->change();
            $table->longText('konsultasipotensipendapatan')->nullable()->change();
            $table->longText('konsultasirencana')->nullable()->change();
            $table->longText('konsultasikendala')->nullable()->change();
            $table->longText('konsultasiisu')->nullable()->change();
            $table->longText('konsultasipembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatankonsultasi', function (Blueprint $table) {
            $table->text('konsultasilembaga')->nullable()->change();
            $table->text('konsultasikomponen')->nullable()->change();
            $table->text('konsultasiyangdilakukan')->nullable()->change();
            $table->text('konsultasihasil')->nullable()->change();
            $table->text('konsultasipotensipendapatan')->nullable()->change();
            $table->text('konsultasirencana')->nullable()->change();
            $table->text('konsultasikendala')->nullable()->change();
            $table->text('konsultasiisu')->nullable()->change();
            $table->text('konsultasipembelajaran')->nullable()->change();
        });
    }
};