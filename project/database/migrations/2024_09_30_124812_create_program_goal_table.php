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
        Schema::create('trprogramgoal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');  // Foreign key to program
            $table->string('deskripsi', 500)->nullable();
            $table->string('indikator', 500)->nullable();
            $table->string('target', 500)->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('program_id')->references('id')->on('trprogram')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trprogramgoal');
    }
};
