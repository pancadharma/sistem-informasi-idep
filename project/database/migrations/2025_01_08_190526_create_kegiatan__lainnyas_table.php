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
        Schema::create('trkegiatanlainnya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('lainnyamengapadilakukan')->nullable();
            $table->text('lainnyadampak')->nullable();
            $table->string('lainnyasumberpendanaan', 200)->nullable();
            $table->text('lainnyasumberpendanaan_ket')->nullable();
            $table->text('lainnyayangterlibat')->nullable();
            $table->text('lainnyarencana')->nullable();
            $table->text('lainnyakendala')->nullable();
            $table->text('lainnyaisu')->nullable();
            $table->text('lainnyapembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatanlainnya');
        Schema::enableForeignKeyConstraints();
    }
};
