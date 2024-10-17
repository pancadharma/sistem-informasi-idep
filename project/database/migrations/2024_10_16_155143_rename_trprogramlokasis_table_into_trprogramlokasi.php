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
        Schema::rename('trprogramlokasis', 'trprogramlokasi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trprogramlokasi', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['provinsi_id']);
            $table->dropColumn('program_id');
            $table->dropColumn('provinsi_id');
        });

        Schema::rename('trprogramlokasi', 'trprogramlokasis');
    }
};