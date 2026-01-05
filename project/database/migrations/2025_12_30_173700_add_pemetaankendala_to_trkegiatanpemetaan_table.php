<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds the missing 'pemetaankendala' column for storing
     * challenges/obstacles data in the pemetaan activity type.
     */
    public function up(): void
    {
        Schema::table('trkegiatanpemetaan', function (Blueprint $table) {
            $table->longText('pemetaankendala')->nullable()->after('pemetaanrencana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpemetaan', function (Blueprint $table) {
            $table->dropColumn('pemetaankendala');
        });
    }
};
