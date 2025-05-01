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
        Schema::table('trprogramoutcomeoutputactivity', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });
        Schema::table('trprogram', function (Blueprint $table) {
            $table->longText('analisamasalah')->nullable()->change();
            $table->longText('deskripsiprojek')->nullable()->change();
        });
        Schema::table('trprogramgoal', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });
        Schema::table('trprogramobjektif', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trprogramoutcomeoutputactivity', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });

        Schema::table('trprogram', function (Blueprint $table) {
            $table->longText('analisamasalah')->nullable()->change();
            $table->longText('deskripsiprojek')->nullable()->change();
        });
        Schema::table('trprogramgoal', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });
        Schema::table('trprogramobjektif', function (Blueprint $table) {
            $table->longText('deskripsi')->nullable()->change();
            $table->longText('indikator')->nullable()->change();
            $table->longText('target')->nullable()->change();
        });
    }
};
