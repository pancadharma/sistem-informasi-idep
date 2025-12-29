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
       Schema::table('feedback', function (Blueprint $table) {
            // Ganti 'tanggal_selesai' dengan nama kolom terakhir yang valid di tabel Anda
            // sebelum penambahan ini, atau hapus ->after() jika ingin di akhir.
            $table->string('field_office')->nullable()->after('tanggal_registrasi');
            $table->boolean('is_hidden')->default(false)->after('status_complaint');
            $table->text('handler_description')->nullable()->after('position_handler');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn('field_office');
            $table->dropColumn('is_hidden');
            $table->dropColumn('handler_description');
        });
    }
};


