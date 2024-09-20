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
        Schema::create('mpendonor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mpendonorkategori_id')->constrained('mpendonorkategori')->onDelete('cascade');
            $table->string('nama', 200);
            $table->string('pic', 200);
            $table->string('email', 200);
            $table->string('phone', 20);
            $table->boolean('aktif')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpendonor');
    }
};
