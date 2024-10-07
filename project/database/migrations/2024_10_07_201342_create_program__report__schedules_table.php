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
        Schema::create('trprogramreportschedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->dateTime('tanggal');
            $table->string('keterangan', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trprogramreportschedule');
    }
};
