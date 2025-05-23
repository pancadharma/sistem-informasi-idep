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
        // Cek apakah tabel sudah ada
        if (Schema::hasTable('trmeals_komponen_model_targetreinstra')) {
            echo "Table 'trmeals_komponen_model_targetreinstra' already exists. Do you want to drop it and create a new one? \nType 'yes' to proceed: ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }

        Schema::dropIfExists('trmeals_komponen_model_targetreinstra');

        Schema::create('trmeals_komponen_model_targetreinstra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mealskomponenmodel_id')->constrained('trmeals_komponen_model')->index('mealskomodel_fk')->onDelete('cascade');
            $table->foreignId('targetreinstra_id')->constrained('mtargetreinstra')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmeals_komponen_model_targetreinstra');
        Schema::enableForeignKeyConstraints();
    }
};