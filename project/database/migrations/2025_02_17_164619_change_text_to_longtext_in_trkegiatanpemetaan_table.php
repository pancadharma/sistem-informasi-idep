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
        Schema::table('trkegiatanpemetaan', function (Blueprint $table) {
            $table->longText('pemetaanyangdihasilkan')->nullable()->change();
            $table->longText('pemetaanluasan')->nullable()->change();
            $table->longText('pemetaanunit')->nullable()->change();
            $table->longText('pemetaanyangterlibat')->nullable()->change();
            $table->longText('pemetaanrencana')->nullable()->change();
            $table->longText('pemetaanisu')->nullable()->change();
            $table->longText('pemetaanpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpemetaan', function (Blueprint $table) {
            $table->text('pemetaanyangdihasilkan')->nullable()->change();
            $table->text('pemetaanluasan')->nullable()->change();
            $table->text('pemetaanunit')->nullable()->change();
            $table->text('pemetaanyangterlibat')->nullable()->change();
            $table->text('pemetaanrencana')->nullable()->change();
            $table->text('pemetaanisu')->nullable()->change();
            $table->text('pemetaanpembelajaran')->nullable()->change();
        });
    }
};