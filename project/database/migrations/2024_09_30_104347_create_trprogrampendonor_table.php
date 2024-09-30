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
        Schema::create('trprogrampendonor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('pendonor_id')->constrained('mpendonor')->onDelete('cascade');
            $table->decimal('nilaidonasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints
        Schema::table('trprogrampendonor', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['pendonor_id']);
        });

        // Drop the table
        Schema::dropIfExists('trprogrampendonor');
    }
};
