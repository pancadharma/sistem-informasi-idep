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
        //drop column desa_id
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropForeign(['mitra_id']);
            $table->dropColumn('desa_id'); // no need because it is already in trkegiatan_lokasi table
            $table->dropColumn('mitra_id'); // no need because it is already in trkegiatan_mitra table
            //set fasepelaporan to 1 if null
            $table->integer('fasepelaporan')->default(1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverse drop column desa_id
        Schema::table('trkegiatan', function (Blueprint $table) {
            $table->foreignId('desa_id')->nullable()->constrained('kelurahan')->onDelete('cascade');
            $table->foreignId('mitra_id')->nullable()->constrained('mpartner')->onDelete('cascade');
            //reverse set fasepelaporan to null
            $table->integer('fasepelaporan')->nullable()->default(1)->change();
        });
    }
};
