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
        Schema::create('trmealskomponenmodel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_program'); // Foreign key for the program
            $table->unsignedBigInteger('id_komponenmodel'); // Foreign key for komponen model
            $table->unsignedBigInteger('id_targetreinstra')->nullable(); // Nullable foreign key for target reinstra
            $table->integer('totaljumlah'); // Integer for total jumlah
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('id_program')->references('id')->on('trprogram')->onDelete('cascade');
            $table->foreign('id_komponenmodel')->references('id')->on('mkomponenmodel')->onDelete('cascade');
            $table->foreign('id_targetreinstra')->references('id')->on('mtargetreinstra')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('trmealskomponenmodel');
        // Schema::table('trmealskomponenmodel', function (Blueprint $table) {
        //     $table->dropForeign(['id_komponenmodel']);
        //     $table->dropForeign(['id_targetreinstra']);
        //     $table->dropForeign(['id_program']);
        // });

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealskomponenmodel');
        Schema::enableForeignKeyConstraints();
    }
};
