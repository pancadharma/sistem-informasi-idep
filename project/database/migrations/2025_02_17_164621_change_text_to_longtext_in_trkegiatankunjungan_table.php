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
        Schema::table('trkegiatankunjungan', function (Blueprint $table) {
            $table->longText('kunjunganlembaga')->nullable()->change();
            $table->longText('kunjunganpeserta')->nullable()->change();
            $table->longText('kunjunganyangdilakukan')->nullable()->change();
            $table->longText('kunjunganhasil')->nullable()->change();
            $table->longText('kunjunganpotensipendapatan')->nullable()->change();
            $table->longText('kunjunganrencana')->nullable()->change();
            $table->longText('kunjungankendala')->nullable()->change();
            $table->longText('kunjunganisu')->nullable()->change();
            $table->longText('kunjunganpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatankunjungan', function (Blueprint $table) {
            $table->text('kunjunganlembaga')->nullable()->change();
            $table->text('kunjunganpeserta')->nullable()->change();
            $table->text('kunjunganyangdilakukan')->nullable()->change();
            $table->text('kunjunganhasil')->nullable()->change();
            $table->text('kunjunganpotensipendapatan')->nullable()->change();
            $table->text('kunjunganrencana')->nullable()->change();
            $table->text('kunjungankendala')->nullable()->change();
            $table->text('kunjunganisu')->nullable()->change();
            $table->text('kunjunganpembelajaran')->nullable()->change();
        });
    }
};