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
        Schema::table('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->string('no_telp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->string('no_telp')->nullable()->change();
        });
    }
};
