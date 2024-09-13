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
        Schema::table('dusun', function (Blueprint $table) {
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->longText('coordinates')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dusun', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('coordinates');
            $table->dropColumn('kode_pos');
            $table->dropTimestamps();
        });
    }
};
