<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateNegaraIdInProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint issues during update or insert
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Check if the column 'negara_id' exists in the 'provinsi' table
        if (!Schema::hasColumn('provinsi', 'negara_id')) {
            // Add the 'negara_id' column if it does not exist
            Schema::table('provinsi', function ($table) {
                $table->foreignId('negara_id')->default(103)->constrained('country')->onDelete('cascade');
            });
        }

        // Update or insert 'negara_id' for all rows in the 'provinsi' table
        $provinsi = DB::table('provinsi')->get();
        foreach ($provinsi as $row) {
            DB::table('provinsi')->updateOrInsert(
                ['id' => $row->id], // Condition to check if the row exists
                ['negara_id' => 103] // Data to update or insert
            );
        }

        // Re-enable foreign key checks after the operation
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}