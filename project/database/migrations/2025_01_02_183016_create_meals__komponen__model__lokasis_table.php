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
            $table->unsignedBigInteger('id_satuan')->nullable(); // Foreign key
            $table->integer('jumlah')->nullable(); // Integer field
            $table->timestamps(); // Timestamps

            // Foreign key constraints in case @siva forgot to add them
            $table->foreign('id_mealskomponenmodel')->references('id')->on('trmealskomponenmodel')->onDelete('cascade');
            $table->foreign('id_dusun')->references('id')->on('dusun')->onDelete('set null');
            $table->foreign('id_desa')->references('id')->on('kelurahan')->onDelete('set null');
            $table->foreign('id_kecamatan')->references('id')->on('kecamatan')->onDelete('set null');
            $table->foreign('id_kabupaten')->references('id')->on('kabupaten')->onDelete('set null');
            $table->foreign('id_provinsi')->references('id')->on('provinsi')->onDelete('set null');
            $table->foreign('id_satuan')->references('id')->on('msatuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('trmealskomponenmodellokasi');
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealskomponenmodellokasi');
        Schema::enableForeignKeyConstraints();
    }
};
