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
        Schema::create('trprogramobjektif', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->unsignedBigInteger('program_id'); // Foreign key for the program
            $table->string('deskripsi', 500)->nullable(); // Description
            $table->string('indikator', 500)->nullable(); // Indicator
            $table->string('target', 500)->nullable(); // Target
            $table->timestamps(); // Created_at and updated_at

            // Add foreign key constraint
            $table->foreign('program_id')->references('id')->on('trprogram')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_objektif');
    }
};
