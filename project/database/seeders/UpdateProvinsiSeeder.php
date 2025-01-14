<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class UpdateProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        try {
            $jsonData = '../database/idep/provinsi.json';
            $provinces = json_decode(file_get_contents($jsonData), true);


            foreach ($provinces as $province) {
                // Convert kode to integer for id
                $id = (int) $province['kode'];

                // Remove any quotes from paths if they exist
                // $paths = str_replace('"', '', $province['paths'] ?? '');
                $paths = $province['paths'] ?? '';

                // Update existing records
                DB::table('provinsi')
                    ->where('id', $id)
                    ->update([
                        'latitude' => $province['lat'] ?? null,
                        'longitude' => $province['lng'] ?? null,
                        'path' => $paths,
                        'updated_at' => Carbon::now(),
                    ]);
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error seeding provinsi: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks even if there's an error
            Schema::enableForeignKeyConstraints();
        }
    }
}
