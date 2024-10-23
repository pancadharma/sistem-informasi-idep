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
        Schema::table('trprogramoutcome', function (Blueprint $table) {
            $table->renameColumn('deskrispsi', 'deskripsi');
        });
        Schema::table('trprogramoutcomeoutput', function (Blueprint $table) {
            $table->renameColumn('deskrispsi', 'deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trprogramoutcome', function (Blueprint $table) {
            $table->renameColumn('deskripsi', 'deskrispsi');
        });
        Schema::table('trprogramoutcomeoutput', function (Blueprint $table) {
            $table->renameColumn('deskripsi', 'deskrispsi');
        });
    }
};
