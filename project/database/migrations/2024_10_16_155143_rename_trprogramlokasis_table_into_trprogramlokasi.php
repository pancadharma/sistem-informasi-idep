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
        // Drop the old foreign key constraints on the old table name
        Schema::table('trprogramlokasis', function (Blueprint $table) {
            // Assuming the original foreign keys are named after the old table name
            $table->dropForeign(['program_id']);
            $table->dropForeign(['provinsi_id']);
        });

        // Rename the table from 'trprogramlokasis' to 'trprogramlokasi'
        Schema::rename('trprogramlokasis', 'trprogramlokasi');
        
        // Add the foreign key constraints back, using the new table name 'trprogramlokasi'
        Schema::table('trprogramlokasi', function (Blueprint $table) {
            $table->foreign('program_id')->references('id')->on('trprogram')->onDelete('cascade');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        });
    }

/**
 * Reverse the migrations.
 */
    public function down(): void
    {
        // Drop the new foreign key constraints
        Schema::table('trprogramlokasi', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['provinsi_id']);
        });

        // Rename the table back to 'trprogramlokasis'
        Schema::rename('trprogramlokasi', 'trprogramlokasis');

        // Add the old foreign key constraints back
        Schema::table('trprogramlokasis', function (Blueprint $table) {
            $table->foreign('program_id')->references('id')->on('trprogram')->onDelete('cascade');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        });
    }
};