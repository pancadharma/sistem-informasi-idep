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
        Schema::create('trprogramkelompokmarjinals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('kelompokmarjinal_id')->constrained('mkelompokmarjinal')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program__kelompok__marjinals');
        // Schema::dropIfExists('trprogramkelompokmarjinals'); //if roleback table should be dropped the same table otherwise if related to different table it could be added here to (if possible)
    }
};
