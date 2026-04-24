<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    { 
        Schema::create('timesheet_entries', function (Blueprint $table) {
            $table->id();

            // HEADER BULANAN
            $table->foreignId('timesheet_id')
                ->constrained()
                ->cascadeOnDelete();

            // TANGGAL HARIAN
            $table->date('work_date');

            // STATUS HARIAN
            $table->enum('day_status', [
                'bekerja',
                'libur',
                'doc',
                'cuti',
                'sakit',
            ])->default('bekerja');

            // LOKASI
            $table->string('location_detail')->nullable();
            $table->string('work_location', 20)->nullable();

            // WAKTU (MENIT)
            $table->integer('minutes')->default(0);

            // DONOR (DB / STATIC)
            $table->foreignId('donor_id')
                ->nullable()
                ->constrained('mpendonor')
                ->nullOnDelete();

            $table->string('donor_static', 50)->nullable();

            // PROGRAM (DB / STATIC)
            $table->foreignId('program_id')
                ->nullable()
                ->constrained('trprogram')
                ->nullOnDelete();

            $table->string('program_static', 50)->nullable();

            // KEGIATAN
            $table->text('activity')->nullable();

            $table->timestamps();

            // INDEX PENTING
            $table->index(['timesheet_id', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheet_entries');
    }
};