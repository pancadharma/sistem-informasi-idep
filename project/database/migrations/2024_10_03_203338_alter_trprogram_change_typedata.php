<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trprogram', function (Blueprint $table) {
            $table->integer('ekspektasipenerimamanfaattidaklangsung')->nullable()->change();

            // Making all the specified fields nullable
            $table->decimal('totalnilai')->nullable()->change();
            $table->integer('ekspektasipenerimamanfaat')->nullable()->change();
            $table->integer('ekspektasipenerimamanfaatwoman')->nullable()->change();
            $table->integer('ekspektasipenerimamanfaatman')->nullable()->change();
            $table->integer('ekspektasipenerimamanfaatgirl')->nullable()->change();
            $table->integer('ekspektasipenerimamanfaatboy')->nullable()->change();
            $table->string('deskripsiprojek', 500)->nullable()->change();
            $table->string('analisamasalah', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('trprogram', function (Blueprint $table) {
            $table->string('ekspektasipenerimamanfaattidaklangsung', 50)->nullable(false)->change();
            $table->decimal('totalnilai')->nullable(false)->change();
            $table->integer('ekspektasipenerimamanfaat')->nullable(false)->change();
            $table->integer('ekspektasipenerimamanfaatwoman')->nullable(false)->change();
            $table->integer('ekspektasipenerimamanfaatman')->nullable(false)->change();
            $table->integer('ekspektasipenerimamanfaatgirl')->nullable(false)->change();
            $table->integer('ekspektasipenerimamanfaatboy')->nullable(false)->change();
            $table->string('deskripsiprojek', 500)->nullable(false)->change();
            $table->string('analisamasalah', 500)->nullable(false)->change();
        });
    }
};
