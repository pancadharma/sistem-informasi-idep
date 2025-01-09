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
            $table->unsignedBigInteger('mealskomponenmodel_id'); // Foreign key
            $table->unsignedBigInteger('dusun_id')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('desa_id')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('kecamatan_id')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('kabupaten_id')->nullable(); // Nullable foreign key
            $table->unsignedBigInteger('provinsi_id')->nullable(); // Nullable foreign key
            $table->double('long')->nullable();
            $table->double('lat')->nullable();
            $table->unsignedBigInteger('satuan_id')->nullable(); // Foreign key
            $table->integer('jumlah')->nullable(); // Integer field
            $table->timestamps(); // Timestamps

            // Foreign key constraints in case @siva forgot to add them
            $table->foreign('mealskomponenmodel_id')->references('id')->on('trmealskomponenmodel')->onDelete('cascade');
            $table->foreign('dusun_id')->references('id')->on('dusun')->onDelete('set null');
            $table->foreign('desa_id')->references('id')->on('kelurahan')->onDelete('set null');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('set null');
            $table->foreign('kabupaten_id')->references('id')->on('kabupaten')->onDelete('set null');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('set null');
            $table->foreign('satuan_id')->references('id')->on('msatuan')->onDelete('cascade');
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
