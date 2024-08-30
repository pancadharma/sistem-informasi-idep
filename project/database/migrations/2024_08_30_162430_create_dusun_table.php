<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dusun', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("kode", 15);
            $table->string("nama", 200);
            // $table->double('latitude')->nullable();
            // $table->double('longitude')->nullable();
            // $table->longText('coordinates')->nullable();
            // $table->string('kode_pos', 5)->nullable();
            $table->integer("aktif")->default(0)->nullable();
            $table->foreignId('desa_id')->constrained('kelurahan')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dusun');
    }
};
