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
        Schema::table('trkegiatanpenulis', function (Blueprint $table) {
            $table->renameColumn('user_id', 'penulis_id')->change();
        });
    }
    // USE CODE THIS FOR alternatif
    // public function up(): void
    // {
    //     Schema::table('trkegiatanpenulis', function (Blueprint $table) {
    //         $table->foreignId('penulis_id')->constrained('users')->onDelete('cascade')->after('user_id');
    //         $table->dropForeign(['user_id']);
    //         $table->dropColumn('user_id');
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpenulis', function (Blueprint $table) {
            $table->renameColumn('user_id', 'penulis_id');
        });
    }
    
};
