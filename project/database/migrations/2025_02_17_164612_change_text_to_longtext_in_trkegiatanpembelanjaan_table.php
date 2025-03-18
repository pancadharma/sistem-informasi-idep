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
        Schema::table('trkegiatanpembelanjaan', function (Blueprint $table) {
            $table->longText('pembelanjaandetailbarang')->nullable()->change();
            $table->longText('pembelanjaanakandistribusi_ket')->nullable()->change();
            $table->longText('pembelanjaankendala')->nullable()->change();
            $table->longText('pembelanjaanisu')->nullable()->change();
            $table->longText('pembelanjaanpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpembelanjaan', function (Blueprint $table) {
            $table->text('pembelanjaandetailbarang')->nullable()->change();
            $table->text('pembelanjaanakandistribusi_ket')->nullable()->change();
            $table->text('pembelanjaankendala')->nullable()->change();
            $table->text('pembelanjaanisu')->nullable()->change();
            $table->text('pembelanjaanpembelajaran')->nullable()->change();
        });
    }
};