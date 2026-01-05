<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. for 2025_03_27_172251_trmeals_penerima_manfaat_jenis_kelompok
     */
    public function up(): void
    {
        if (!Schema::hasTable('trmeals_penerima_manfaat_jenis_kelompok')) {
            Schema::create('trmeals_penerima_manfaat_jenis_kelompok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('trmeals_penerima_manfaat_id')->constrained('trmeals_penerima_manfaat')->index('trmeals_pm_mjk_fk');
                $table->foreignId('jenis_kelompok_id')->constrained('master_jenis_kelompok')->onDelete('cascade')->index('trmeasl_mjk');
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('trmeals_penerima_manfaat_jenis_kelompok', function (Blueprint $table) {
                if (!Schema::hasColumn('trmeals_penerima_manfaat_jenis_kelompok', 'trmeals_penerima_manfaat_id')) {
                    $table->foreignId('trmeals_penerima_manfaat_id')->constrained('trmeals_penerima_manfaat')->index('trmeals_pm_mjk_fk');
                }
                if (!Schema::hasColumn('trmeals_penerima_manfaat_jenis_kelompok', 'jenis_kelompok_id')) {
                    $table->foreignId('jenis_kelompok_id')->constrained('master_jenis_kelompok')->onDelete('cascade')->index('trmeasl_mjk');
                }
                if (!Schema::hasColumn('trmeals_penerima_manfaat_jenis_kelompok', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmeals_penerima_manfaat_jenis_kelompok');
        Schema::enableForeignKeyConstraints();
    }
};
