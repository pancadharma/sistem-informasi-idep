<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * using mpartner instead of partners
     */
    public function up(): void
    {
        Schema::create('mpartner', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("nama", 200);
            $table->longText("keterangan")->nullable();
            $table->integer("aktif")->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpartner');
    }
};
