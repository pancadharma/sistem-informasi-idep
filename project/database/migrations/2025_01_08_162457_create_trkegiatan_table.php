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
        Schema::create('trkegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programoutcomeoutputactivity_id')->constrained('trprogramoutcomeoutputactivity')->onDelete('cascade');
            $table->integer('fasepelaporan');
            $table->foreignId('jeniskegiatan_id')->constrained('mjeniskegiatan')->onDelete('cascade');
            $table->foreignId('desa_id')->constrained('kelurahan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('lokasi')->nullable();
            $table->double('long')->nullable();
            $table->double('lat')->nullable();
            $table->dateTime('tanggalmulai');
            $table->dateTime('tanggalselesai');
            $table->string('status', 50);
            $table->string('mitra', 300)->nullable();
            $table->text('deskripsilatarbelakang')->nullable();
            $table->text('deskripsitujuan')->nullable();
            $table->text('deskripsikeluaran')->nullable();
            $table->text('deskripsiyangdikaji')->nullable();
            $table->integer('penerimamanfaatdewasaperempuan')->nullable();
            $table->integer('penerimamanfaatdewasalakilaki')->nullable();
            $table->integer('penerimamanfaatdewasatotal')->nullable();
            $table->integer('penerimamanfaatlansiaperempuan')->nullable();
            $table->integer('penerimamanfaatlansialakilaki')->nullable();
            $table->integer('penerimamanfaatlansiatotal')->nullable();
            $table->integer('penerimamanfaatremajaperempuan')->nullable();
            $table->integer('penerimamanfaatremajalakilaki')->nullable();
            $table->integer('penerimamanfaatremajatotal')->nullable();
            $table->integer('penerimamanfaatanakperempuan')->nullable();
            $table->integer('penerimamanfaatanaklakilaki')->nullable();
            $table->integer('penerimamanfaatanaktotal')->nullable();
            $table->integer('penerimamanfaatdisabilitasperempuan')->nullable();
            $table->integer('penerimamanfaatdisabilitaslakilaki')->nullable();
            $table->integer('penerimamanfaatdisabilitastotal')->nullable();
            $table->integer('penerimamanfaatnondisabilitasperempuan')->nullable();
            $table->integer('penerimamanfaatnondisabilitaslakilaki')->nullable();
            $table->integer('penerimamanfaatnondisabilitastotal')->nullable();
            $table->integer('penerimamanfaatmarjinalperempuan')->nullable();
            $table->integer('penerimamanfaatmarjinallakilaki')->nullable();
            $table->integer('penerimamanfaatmarjinaltotal')->nullable();
            $table->integer('penerimamanfaatperempuantotal')->nullable();
            $table->integer('penerimamanfaatlakilakitotal')->nullable();
            $table->integer('penerimamanfaattotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatan');
        Schema::enableForeignKeyConstraints();
    }
};
