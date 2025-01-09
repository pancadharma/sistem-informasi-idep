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
        Schema::create('trkegiatan_mitra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->foreignId('mitra_id')->constrained('mpartner')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['kegiatan_id', 'mitra_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatan_mitra');
        Schema::table('trkegiatan_mitra', function (Blueprint $table) {
            $table->dropUnique(['kegiatan_id', 'mitra_id']);
        });
        Schema::enableForeignKeyConstraints();
    }
};
