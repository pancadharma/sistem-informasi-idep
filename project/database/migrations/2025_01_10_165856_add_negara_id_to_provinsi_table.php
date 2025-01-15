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
        Schema::table('provinsi', function (Blueprint $table) {
            $table->foreignId('negara_id')->nullable()->constrained('country')->onDelete('cascade');
        });

        Schema::table('provinsi', function (Blueprint $table) {
            $table->foreignId('negara_id')->nullable()->change()->after('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->dropForeign(['negara_id']);
            $table->dropColumn('negara_id');
        });
    }
};