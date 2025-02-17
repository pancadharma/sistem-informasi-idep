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
        Schema::table('trkegiatankampanye', function (Blueprint $table) {
            $table->longText('kampanyeyangdikampanyekan')->nullable()->change();
            $table->longText('kampanyebentukkegiatan')->nullable()->change();
            $table->longText('kampanyeyangterlibat')->nullable()->change();
            $table->longText('kampanyeyangdisasar')->nullable()->change();
            $table->longText('kampanyejangkauan')->nullable()->change();
            $table->longText('kampanyerencana')->nullable()->change();
            $table->longText('kampanyekendala')->nullable()->change();
            $table->longText('kampanyeisu')->nullable()->change();
            $table->longText('kampanyepembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatankampanye', function (Blueprint $table) {
            $table->text('kampanyeyangdikampanyekan')->nullable()->change();
            $table->text('kampanyebentukkegiatan')->nullable()->change();
            $table->text('kampanyeyangterlibat')->nullable()->change();
            $table->text('kampanyeyangdisasar')->nullable()->change();
            $table->text('kampanyejangkauan')->nullable()->change();
            $table->text('kampanyerencana')->nullable()->change();
            $table->text('kampanyekendala')->nullable()->change();
            $table->text('kampanyeisu')->nullable()->change();
            $table->text('kampanyepembelajaran')->nullable()->change();


        });
    }
};