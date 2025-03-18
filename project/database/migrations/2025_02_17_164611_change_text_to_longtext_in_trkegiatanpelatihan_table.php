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
        Schema::table('trkegiatanpelatihan', function (Blueprint $table) {
            $table->longText('pelatihanpelatih')->nullable()->change();
            $table->longText('pelatihanhasil')->nullable()->change();
            $table->longText('pelatihandistribusi_ket')->nullable()->change();
            $table->longText('pelatihanrencana')->nullable()->change();
            $table->longText('pelatihanisu')->nullable()->change();
            $table->longText('pelatihanpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpelatihan', function (Blueprint $table) {
            $table->text('pelatihanpelatih')->nullable()->change();
            $table->text('pelatihanhasil')->nullable()->change();
            $table->text('pelatihandistribusi_ket')->nullable()->change();
            $table->text('pelatihanrencana')->nullable()->change();
            $table->text('pelatihanisu')->nullable()->change();
            $table->text('pelatihanpembelajaran')->nullable()->change();
        });
    }
};