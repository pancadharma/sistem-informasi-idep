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
        Schema::create('trprogramoutcomeoutput', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programoutcome_id')->constrained('trprogramoutcome')->cascadeOnDelete();
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
        Schema::dropIfExists('trprogramoutcomeoutput');
    }
};
