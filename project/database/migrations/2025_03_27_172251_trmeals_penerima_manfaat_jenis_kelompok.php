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
        //jenis_kelompok -> master_jenis_kelompok

        // check if the table is already exists and ask the user confirmation
        if (Schema::hasTable('trmeals_penerima_manfaat_jenis_kelompok')) {
            echo "Table 'trmeals_penerima_manfaat_jenis_kelompok' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_penerima_manfaat_jenis_kelompok');

        Schema::create('trmeals_penerima_manfaat_jenis_kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trmeals_penerima_manfaat_id')->constrained('trmeals_penerima_manfaat')->index('trmeals_pm_mjk_fk');
            $table->foreignId('jenis_kelompok_id')->constrained('master_jenis_kelompok')->onDelete('cascade')->index('trmeasl_mjk');
            $table->timestamps();
            $table->softDeletes();
        });
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
