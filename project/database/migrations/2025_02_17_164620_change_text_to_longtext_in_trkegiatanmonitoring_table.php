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
        Schema::table('trkegiatanmonitoring', function (Blueprint $table) {
            $table->longText('monitoringyangdipantau')->nullable()->change();
            $table->longText('monitoringdata')->nullable()->change();
            $table->longText('monitoringyangterlibat')->nullable()->change();
            $table->longText('monitoringmetode')->nullable()->change();
            $table->longText('monitoringhasil')->nullable()->change();
            $table->longText('monitoringkegiatanselanjutnya_ket')->nullable()->change();
            $table->longText('monitoringkendala')->nullable()->change();
            $table->longText('monitoringisu')->nullable()->change();
            $table->longText('monitoringpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanmonitoring', function (Blueprint $table) {
            $table->text('monitoringyangdipantau')->nullable()->change();
            $table->text('monitoringdata')->nullable()->change();
            $table->text('monitoringyangterlibat')->nullable()->change();
            $table->text('monitoringmetode')->nullable()->change();
            $table->text('monitoringhasil')->nullable()->change();
            $table->text('monitoringkegiatanselanjutnya_ket')->nullable()->change();
            $table->text('monitoringkendala')->nullable()->change();
            $table->text('monitoringisu')->nullable()->change();
            $table->text('monitoringpembelajaran')->nullable()->change();


        });
    }
};