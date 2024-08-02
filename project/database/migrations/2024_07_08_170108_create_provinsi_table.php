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
        Schema::create('provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 15);
            $table->string('nama', 200);
            $table->string('kota', 50);
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->longText('path')->nullable();
            $table->longText('coordinates')->nullable();
            $table->boolean('aktif')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinsi');
    }
};
