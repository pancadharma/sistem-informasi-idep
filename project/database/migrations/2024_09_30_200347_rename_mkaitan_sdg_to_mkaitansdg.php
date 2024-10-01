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
        Schema::table('mkaitan_sdg', function (Blueprint $table) {
            // Renaming the table from mkaitan_sdg to mkaitansdg
            Schema::rename('mkaitan_sdg', 'mkaitansdg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mkaitan_sdg', function (Blueprint $table) {
            // Reverting back the table name to mkaitan_sdg
            Schema::rename('mkaitansdg', 'mkaitan_sdg');
        });
    }
};
