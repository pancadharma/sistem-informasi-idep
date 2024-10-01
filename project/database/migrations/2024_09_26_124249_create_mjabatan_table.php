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
        Schema::create('mjabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200); // Nama field with a max length of 200
            $table->boolean('aktif')->default(1); // Aktif field as boolean (bit in SQL)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mjabatan');
    }
};
