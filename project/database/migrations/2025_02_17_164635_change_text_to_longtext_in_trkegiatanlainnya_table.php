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
        Schema::table('trkegiatanlainnya', function (Blueprint $table) {
            $table->longText('lainnyamengapadilakukan')->nullable()->change();
            $table->longText('lainnyadampak')->nullable()->change();
            $table->longText('lainnyasumberpendanaan_ket')->nullable()->change();
            $table->longText('lainnyayangterlibat')->nullable()->change();
            $table->longText('lainnyarencana')->nullable()->change();
            $table->longText('lainnyakendala')->nullable()->change();
            $table->longText('lainnyaisu')->nullable()->change();
            $table->longText('lainnyapembelajaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trkegiatanlainnya', function (Blueprint $table) {
            $table->text('lainnyamengapadilakukan')->nullable()->change();
            $table->text('lainnyadampak')->nullable()->change();
            $table->text('lainnyasumberpendanaan_ket')->nullable()->change();
            $table->text('lainnyayangterlibat')->nullable()->change();
            $table->text('lainnyarencana')->nullable()->change();
            $table->text('lainnyakendala')->nullable()->change();
            $table->text('lainnyaisu')->nullable()->change();
            $table->text('lainnyapembelajaran')->nullable()->change();
        });
    }
};