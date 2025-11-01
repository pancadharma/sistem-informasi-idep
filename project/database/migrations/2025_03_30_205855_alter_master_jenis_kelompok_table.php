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
        //update the table  master_jenis_kelompok add column deleted_at
        // check if table already has deleted_at column
        if (Schema::hasColumn('master_jenis_kelompok', 'deleted_at')) {
            echo "Column 'deleted_at' already exists in table \n type yes to proceed : ";
            $confirmation = strtolower(trim(fgets(STDIN)));
            if ($confirmation !== 'yes') {
                return;
            }

            Schema::table('master_jenis_kelompok', function (Blueprint $table) {
                $table->dropColumn(['deleted_at']);
            });
        }

        Schema::table('master_jenis_kelompok', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_jenis_kelompok', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
    }
};
