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
        Schema::table('trkegiatanassessment', function (Blueprint $table) {
            $table->longText('assessmentyangterlibat')->nullable()->change();
            $table->longText('assessmenttemuan')->nullable()->change();
            $table->longText('assessmenttambahan_ket')->nullable()->change();
            $table->longText('assessmentkendala')->nullable()->change();
            $table->longText('assessmentisu')->nullable()->change();
            $table->longText('assessmentpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanassessment', function (Blueprint $table) {
            $table->text('assessmentyangterlibat')->nullable()->change();
            $table->text('assessmenttemuan')->nullable()->change();
            $table->text('assessmenttambahan_ket')->nullable()->change();
            $table->text('assessmentkendala')->nullable()->change();
            $table->text('assessmentisu')->nullable()->change();
            $table->text('assessmentpembelajaran')->nullable()->change();
        });
    }
};