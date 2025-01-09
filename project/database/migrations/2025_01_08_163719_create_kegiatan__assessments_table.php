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
        Schema::create('trkegiatanassessment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('assessmentyangterlibat')->nullable();
            $table->text('assessmenttemuan')->nullable();
            $table->boolean('assessmenttambahan')->default(false);
            $table->text('assessmenttambahan_ket')->nullable();
            $table->text('assessmentkendala')->nullable();
            $table->text('assessmentisu')->nullable();
            $table->text('assessmentpembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanassessment');
        Schema::enableForeignKeyConstraints();
    }
};
