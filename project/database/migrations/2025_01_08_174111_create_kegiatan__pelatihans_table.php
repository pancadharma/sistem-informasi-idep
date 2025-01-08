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
        Schema::create('trkegiatanpelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('pelatihanpelatih')->nullable();
            $table->text('pelatihanhasil')->nullable();
            $table->boolean('pelatihandistribusi')->default(false);
            $table->text('pelatihandistribusi_ket')->nullable();
            $table->text('pelatihanrencana')->nullable();
            $table->boolean('pelatihanunggahan')->default(false);
            $table->text('pelatihanisu')->nullable();
            $table->text('pelatihanpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanpelatihan');
        Schema::enableForeignKeyConstraints();
    }
};
