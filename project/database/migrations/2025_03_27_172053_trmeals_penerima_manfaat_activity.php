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
        if (Schema::hasTable('trmeals_penerima_manfaat_activity')) {
            echo "Table 'trmeals_penerima_manfaat_activity' already exists. Do you want to drop it and create a new one? \n type 'yes' to proceed : ";

            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }
        }
        Schema::dropIfExists('trmeals_penerima_manfaat_activity');

        Schema::create('trmeals_penerima_manfaat_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trmeals_penerima_manfaat_id')->constrained('trmeals_penerima_manfaat')->index('trmeals_pm_fk');
            $table->foreignId('trprogramoutcomeoutputactivity_id')->constrained('trprogramoutcomeoutput')->onDelete('cascade')->index('trmeals_pm_activity_id_fk');

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
        Schema::dropIfExists('trmeals_penerima_manfaat_activity');
        Schema::enableForeignKeyConstraints();
    }
};
