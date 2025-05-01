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
        if (Schema::hasTable('trmeals_target_progress_detail')) {
            echo "Table 'trmeals_target_progress_detail' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_target_progress_detail');

        Schema::create('trmeals_target_progress_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_meals_target_progress')->constrained('trmeals_target_progress')->onDelete('cascade');
            $table->integer('id_target');
            $table->string('tipe', 50);
            $table->string('achievements', 500)->nullable();
            $table->integer('progress')->nullable();
            $table->decimal('persentase_complete', 5, 2)->nullable();
            $table->string('status', 50);
            $table->text('challenges')->nullable();
            $table->text('mitigation')->nullable();
            $table->string('risk', 50)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('trmeals_target_progress_detail');
        Schema::enableForeignKeyConstraints();
    }
};
