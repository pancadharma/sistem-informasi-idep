<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trprogram', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('kode', 50);
            $table->dateTime('tanggalmulai');
            $table->dateTime('tanggalselesai');
            $table->decimal('totalnilai', 15, 2);
            $table->integer('ekspektasipenerimamanfaat');
            $table->integer('ekspektasipenerimamanfaatwoman');
            $table->integer('ekspektasipenerimamanfaatman');
            $table->integer('ekspektasipenerimamanfaatgirl');
            $table->integer('ekspektasipenerimamanfaatboy');
            $table->string('ekspektasipenerimamanfaattidaklangsung', 100)->nullable();
            $table->string('deskripsiprojek', 500)->nullable();
            $table->string('analisamasalah', 500)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('submit')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trprogram');
    }
};
