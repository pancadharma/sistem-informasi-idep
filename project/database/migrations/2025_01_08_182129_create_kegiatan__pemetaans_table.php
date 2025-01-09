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
        Schema::create('trkegiatanpemetaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('pemetaanyangdihasilkan')->nullable();
            $table->text('pemetaanluasan')->nullable();
            $table->text('pemetaanunit')->nullable();
            $table->text('pemetaanyangterlibat')->nullable();
            $table->text('pemetaanrencana')->nullable();
            $table->text('pemetaanisu')->nullable();
            $table->text('pemetaanpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanpemetaan');
        Schema::enableForeignKeyConstraints();
    }
};
