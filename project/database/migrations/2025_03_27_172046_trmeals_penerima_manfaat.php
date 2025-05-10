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
        // check if the table is already exists and ask the user confirmation
        if (Schema::hasTable('trmeals_penerima_manfaat')) {
            echo "Table 'trmeals_penerima_manfaat' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_penerima_manfaat');

        Schema::create('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dusun_id')->references('id')->on('dusun')->onDelete('cascade');
            $table->string('nama', 255);
            $table->string('no_telp', 15);
            $table->string('jenis_kelamin', 20);
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->integer('umur');
            $table->longText('keterangan')->nullable();
            $table->boolean('is_non_activity')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // beneficiary has many kelompok_marjinal and jenis_kelompok

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmeals_penerima_manfaat');
        Schema::enableForeignKeyConstraints();
    }
};
