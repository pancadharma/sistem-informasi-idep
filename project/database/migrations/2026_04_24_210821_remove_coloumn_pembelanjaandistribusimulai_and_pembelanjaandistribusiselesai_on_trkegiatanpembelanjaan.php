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
            $table->dropColumn('pembelanjaandistribusimulai');
            $table->dropColumn('pembelanjaandistribusiselesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpembelanjaan', function (Blueprint $table) {
            $table->dateTime('pembelanjaandistribusimulai')->nullable();
            $table->dateTime('pembelanjaandistribusiselesai')->nullable();
        });
    }
};
