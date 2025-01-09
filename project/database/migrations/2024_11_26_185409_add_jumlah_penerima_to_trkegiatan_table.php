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
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->integer('jumlahpenerimamanfaat')->nullable();
            $table->integer('jumlahpenerimamanfaatwoman')->nullable();
            $table->integer('jumlahpenerimamanfaatman')->nullable();
            $table->integer('jumlahpenerimamanfaatgirl')->nullable();
            $table->integer('jumlahpenerimamanfaatboy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->dropColumn([
                'jumlahpenerimamanfaat',
                'jumlahpenerimamanfaatwoman',
                'jumlahpenerimamanfaatman',
                'jumlahpenerimamanfaatgirl',
                'jumlahpenerimamanfaatboy',
            ]);
        });
    }
};
