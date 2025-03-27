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
        Schema::create('trmeals_penerima_manfaat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trpgrogram')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dusun_id')->references('id')->on('dusun')->onDelete('cascade');
            $table->string('nama', 255);
            $table->string('no_telp', 15);
            $table->string('jenis_kelamin', 20);
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->integer('umur');
            $table->longText('keterangan')->nullable();
            $table->string('kelompok_marjinal', 255);
            $table->boolean('is_non_activity')->default(false);
            $table->timestamps();

            // beneficiary has many kelompok_marjinal and jenis_kelompok

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};