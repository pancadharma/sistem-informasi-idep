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
        Schema::create('trmealskomponenmodellokasi', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('id_mealskomponenmodel'); // Foreign key
            $table->unsignedBigInteger('id_dusun')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('id_desa')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('id_kecamatan')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('id_kabupaten')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('id_provinsi')->nullable(); // Nullable foreign key
            $table->decimal('long', 9, 6)->nullable(); // Longitude
            $table->decimal('lat', 9, 6)->nullable(); // Latitude
            $table->unsignedBigInteger('id_satuan'); // Foreign key
            $table->integer('jumlah'); // Integer field
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trmealskomponenmodellokasi');
    }
};
