<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. 2025_03_27_172239_trmeals_penerima_manfaat_kelompok_marjinal
     */
    public function up(): void
    {
        //mkelompokmarjinal > master table

        if (Schema::hasTable('trmeals_penerima_manfaat_kelompok_marjinal')) {
            echo "Table 'trmeals_penerima_manfaat_kelompok_marjinal' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_penerima_manfaat_kelompok_marjinal');
        Schema::create('trmeals_penerima_manfaat_kelompok_marjinal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trmeals_penerima_manfaat_id')->constrained('trmeals_penerima_manfaat')->index('trmeals_pm_km_fk');
            $table->foreignId('kelompok_marjinal_id')->constrained('mkelompokmarjinal')->onDelete('cascade')->index('trmeals_km_pm_fk');
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
        Schema::dropIfExists('trmeals_penerima_manfaat_kelompok_marjinal');
        Schema::enableForeignKeyConstraints();
    }
};
