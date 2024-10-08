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
        Schema::create('trprogramoutcome', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->cascadeOnDelete();
            $table->longText('deskrispsi')->nullable();
            $table->longText('indikator')->nullable();
            $table->longText('target')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trprogramoutcome');
    }
};
