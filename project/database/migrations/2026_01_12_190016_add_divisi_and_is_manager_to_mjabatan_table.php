<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mjabatan', function (Blueprint $table) {

            if (!Schema::hasColumn('mjabatan', 'divisi_id')) {
                $table->unsignedBigInteger('divisi_id')->nullable()->after('nama');
            }

            if (!Schema::hasColumn('mjabatan', 'is_manager')) {
                $table->boolean('is_manager')->default(false)->after('divisi_id');
            }

            $table->foreign('divisi_id')
                ->references('id')
                ->on('mdivisi')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mjabatan', function (Blueprint $table) {
            if (Schema::hasColumn('mjabatan', 'divisi_id')) {
                $table->dropForeign(['divisi_id']);
                $table->dropColumn('divisi_id');
            }

            if (Schema::hasColumn('mjabatan', 'is_manager')) {
                $table->dropColumn('is_manager');
            }
        });
    }
};
