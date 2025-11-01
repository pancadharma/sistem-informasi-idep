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
        Schema::dropIfExists('trmealskomponenmodel');
        Schema::enableForeignKeyConstraints();
        // Schema::dropIfExists('trmealskomponenmodel');

        // Cek apakah tabel sudah ada
        if (Schema::hasTable('trmeals_komponen_model')) {
            echo "Table 'trmeals_komponen_model' already exists. Do you want to drop it and create a new one? \nType 'yes' to proceed: ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }

        Schema::dropIfExists('trmeals_komponen_model');

        Schema::create('trmeals_komponen_model', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('komponenmodel_id')->nullable()->constrained('mkomponenmodel')->onDelete('cascade');
            $table->integer('totaljumlah')->nullable();
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
        Schema::dropIfExists('trmeals_komponen_model');
        Schema::enableForeignKeyConstraints();
    }
};
