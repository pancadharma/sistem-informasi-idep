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
        Schema::create('trmealsfrm', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('program_id'); // FK: Program

            $table->dateTime('tanggalregistrasi');
            $table->integer('umur');
            $table->string('jeniskelamin', 50);
            $table->string('statuskomplain', 50);
            $table->string('notelp', 20);
            $table->string('alamat', 500);
            $table->boolean('hide')->default(0);

            // Receiver Information
            $table->unsignedBigInteger('userpenerima_id'); // FK: User
            $table->unsignedBigInteger('jabatanpenerima_id'); // FK: Mjabatan
            $table->string('notelppenerima', 20);

            // Complaint Details
            $table->string('channels', 100);
            $table->string('channelslainnya', 100)->nullable();
            $table->string('kategorikomplain', 100);
            $table->string('deskripsikomplain', 500);
            $table->dateTime('tanggalselesai')->nullable();

            // Handler Information
            $table->unsignedBigInteger('userhandler_id'); // FK: User
            $table->unsignedBigInteger('jabatanhandler_id'); // FK: Mjabatan
            $table->string('notelphandler', 20);
            $table->string('deskripsi', 500);

            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('program_id')->references('id')->on('trprogram');
            $table->foreign('userpenerima_id')->references('id')->on('users');
            $table->foreign('jabatanpenerima_id')->references('id')->on('mjabatan');
            $table->foreign('userhandler_id')->references('id')->on('users');
            $table->foreign('jabatanhandler_id')->references('id')->on('mjabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals__feedback__responses');
    }
};
