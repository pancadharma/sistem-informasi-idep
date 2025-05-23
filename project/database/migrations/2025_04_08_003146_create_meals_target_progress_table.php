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
        // check if the table is already exists and ask the user confirmation
        if (Schema::hasTable('trmeals_target_progress')) {
            echo "Table 'trmeals_target_progress' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_target_progress');

        Schema::create('trmeals_target_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('trprogram')->onDelete('cascade');
            $table->dateTime('tanggal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmeals_target_progress');
        Schema::enableForeignKeyConstraints();
    }
};
