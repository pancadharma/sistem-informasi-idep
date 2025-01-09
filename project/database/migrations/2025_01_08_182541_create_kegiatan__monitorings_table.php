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
        Schema::create('trkegiatanmonitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('monitoringyangdipantau')->nullable();
            $table->text('monitoringdata')->nullable();
            $table->text('monitoringyangterlibat')->nullable();
            $table->text('monitoringmetode')->nullable();
            $table->text('monitoringhasil')->nullable();
            $table->boolean('monitoringkegiatanselanjutnya')->default(false);
            $table->text('monitoringkegiatanselanjutnya_ket')->nullable();
            $table->text('monitoringkendala')->nullable();
            $table->text('monitoringisu')->nullable();
            $table->text('monitoringpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanmonitoring');
        Schema::enableForeignKeyConstraints();
    }
};
