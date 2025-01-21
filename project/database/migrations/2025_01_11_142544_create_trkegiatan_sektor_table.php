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
        Schema::create('trkegiatan_sektor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->nullable()->constrained('trkegiatan')->onDelete('cascade');
            $table->foreignId('sektor_id')->nullable()->constrained('msektor')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatan_sektor');
        Schema::enableForeignKeyConstraints();
    }
};
