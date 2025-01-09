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
            $table->unsignedBigInteger('program_id'); // Foreign key for the program
            $table->unsignedBigInteger('komponenmodel_id'); // Foreign key for komponen model
            $table->unsignedBigInteger('targetreinstra_id')->nullable(); // Nullable foreign key for target reinstra
            $table->integer('totaljumlah'); // Integer for total jumlah
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('program_id')->references('id')->on('trprogram')->onDelete('cascade');
            $table->foreign('komponenmodel_id')->references('id')->on('mkomponenmodel')->onDelete('cascade');
            $table->foreign('targetreinstra_id')->references('id')->on('mtargetreinstra')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     * updated on 09-01-2025
     * make sure the foreign key constraints are removed before dropping the tabl
     * also updating foreign key column name to match laravel for _id behind the name
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmealskomponenmodel');
        Schema::enableForeignKeyConstraints();
    }
};
