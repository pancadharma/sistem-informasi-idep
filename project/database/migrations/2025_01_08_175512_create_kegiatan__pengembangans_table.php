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
        Schema::create('trkegiatanpengembangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('pengembanganjeniskomponen')->nullable();
            $table->text('pengembanganberapakomponen')->nullable();
            $table->text('pengembanganlokasikomponen')->nullable();
            $table->text('pengembanganyangterlibat')->nullable();
            $table->text('pengembanganrencana')->nullable();
            $table->text('pengembangankendala')->nullable();
            $table->text('pengembanganisu')->nullable();
            $table->text('pengembanganpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanpengembangan');
        Schema::enableForeignKeyConstraints();
    }
};
