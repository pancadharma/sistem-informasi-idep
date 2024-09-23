<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. after
     * php artisan db:seed --class=FactoryTargetReinstraSeeder for generate random seed
     */
    public function up(): void
    {
        Schema::create('mtargetreinstra', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->integer('aktif')->defaultValue(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtargetreinstra');
    }
};
