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
            if (Schema::hasColumn('trprogramoutcome', 'deskrispsi')) {
                $table->dropColumn('deskrispsi'); // Drop the old column
                $table->longText('deskripsi')->nullable(); // Add the new column
            }
        });

        Schema::table('trprogramoutcomeoutput', function (Blueprint $table) {
            if (Schema::hasColumn('trprogramoutcomeoutput', 'deskrispsi')) {
                $table->dropColumn('deskrispsi'); // Drop the old column
                $table->longText('deskripsi')->nullable(); // Add the new column
            }
        });
    }

    public function down(): void
    {
        Schema::table('trprogramoutcome', function (Blueprint $table) {
            if (Schema::hasColumn('trprogramoutcome', 'deskripsi')) {
                $table->dropColumn('deskripsi'); // Drop the new column
                $table->longText('deskrispsi')->nullable(); // Add back the old column
            }
        });

        Schema::table('trprogramoutcomeoutput', function (Blueprint $table) {
            if (Schema::hasColumn('trprogramoutcomeoutput', 'deskripsi')) {
                $table->dropColumn('deskripsi'); // Drop the new column
                $table->longText('deskrispsi')->nullable(); // Add back the old column
            }
        });
    }
};
