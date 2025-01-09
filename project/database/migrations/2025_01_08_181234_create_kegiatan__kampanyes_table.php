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
        Schema::create('trkegiatankampanye', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('trkegiatan')->onDelete('cascade');
            $table->text('kampanyeyangdikampanyekan')->nullable();
            $table->string('kampanyejenis', 200)->nullable();
            $table->text('kampanyebentukkegiatan')->nullable();
            $table->text('kampanyeyangterlibat')->nullable();
            $table->text('kampanyeyangdisasar')->nullable();
            $table->text('kampanyejangkauan')->nullable();
            $table->text('kampanyerencana')->nullable();
            $table->text('kampanyekendala')->nullable();
            $table->text('kampanyeisu')->nullable();
            $table->text('kampanyepembelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trkegiatankampanye');
        Schema::enableForeignKeyConstraints();
    }
};
