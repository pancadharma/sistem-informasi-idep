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
        Schema::table('trkegiatanpengembangan', function (Blueprint $table) {
            $table->longText('pengembanganjeniskomponen')->nullable()->change();
            $table->longText('pengembanganberapakomponen')->nullable()->change();
            $table->longText('pengembanganlokasikomponen')->nullable()->change();
            $table->longText('pengembanganyangterlibat')->nullable()->change();
            $table->longText('pengembanganrencana')->nullable()->change();
            $table->longText('pengembangankendala')->nullable()->change();
            $table->longText('pengembanganisu')->nullable()->change();
            $table->longText('pengembanganpembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanpengembangan', function (Blueprint $table) {
            $table->text('pengembanganjeniskomponen')->nullable()->change();
            $table->text('pengembanganberapakomponen')->nullable()->change();
            $table->text('pengembanganlokasikomponen')->nullable()->change();
            $table->text('pengembanganyangterlibat')->nullable()->change();
            $table->text('pengembanganrencana')->nullable()->change();
            $table->text('pengembangankendala')->nullable()->change();
            $table->text('pengembanganisu')->nullable()->change();
            $table->text('pengembanganpembelajaran')->nullable()->change();
        });
    }
};