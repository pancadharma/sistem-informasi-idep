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

        // hapus table lama jika ada
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealskomponenmodellokasi');
        Schema::enableForeignKeyConstraints();

        // Cek apakah tabel sudah ada
        if (Schema::hasTable('trmeals_komponen_model_lokasi')) {
            echo "Table 'trmeals_komponen_model_lokasi' already exists. Do you want to drop it and create a new one? \nType 'yes' to proceed: ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }

        Schema::dropIfExists('trmeals_komponen_model_lokasi');

        Schema::create('trmeals_komponen_model_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mealskomponenmodel_id')->constrained('trmeals_komponen_model')->onDelete('cascade');
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained('kelurahan')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatan')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupaten')->onDelete('cascade');
            $table->foreignId('provinsi_id')->nullable()->constrained('provinsi')->onDelete('cascade');
            $table->decimal('long', 9, 6)->nullable();
            $table->decimal('lat', 9, 6)->nullable();
            $table->foreignId('satuan_id')->nullable()->constrained('msatuan')->onDelete('cascade');
            $table->integer('jumlah')->nullable();
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
        Schema::dropIfExists('trmeals_komponen_model_lokasi');
        Schema::enableForeignKeyConstraints();
    }
};
