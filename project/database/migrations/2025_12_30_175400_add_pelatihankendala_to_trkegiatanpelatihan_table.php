<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds the missing 'pelatihankendala' column for storing
     * challenges/obstacles data in the pelatihan activity type.
     */
    public function up(): void
    {
        Schema::table('trkegiatanpelatihan', function (Blueprint $table) {
            $table->longText('pelatihankendala')->nullable()->after('pelatihanunggahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpelatihan', function (Blueprint $table) {
            $table->dropColumn('pelatihankendala');
        });
    }
};
