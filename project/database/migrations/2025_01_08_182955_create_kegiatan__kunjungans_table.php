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
        Schema::create('trkegiatankunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('kunjunganlembaga')->nullable();
            $table->text('kunjunganpeserta')->nullable();
            $table->text('kunjunganyangdilakukan')->nullable();
            $table->text('kunjunganhasil')->nullable();
            $table->text('kunjunganpotensipendapatan')->nullable();
            $table->text('kunjunganrencana')->nullable();
            $table->text('kunjungankendala')->nullable();
            $table->text('kunjunganisu')->nullable();
            $table->text('kunjunganpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatankunjungan');
        Schema::enableForeignKeyConstraints();
    }
};
