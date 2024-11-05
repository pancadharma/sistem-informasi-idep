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
        Schema::table('trprogrampendonor', function (Blueprint $table) {
            $table->decimal('nilaidonasi', 20, 2)->nullable()->change();
        });

        Schema::table('trprogram', function (Blueprint $table) {
            $table->decimal('totalnilai', 20, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
