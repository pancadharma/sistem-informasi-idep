<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dusun;
use App\Models\Desa; // Import the Desa model
use App\Models\Kelurahan;
use App\Models\Program;

class DusunSeederFaker extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dusun::factory()->count(100)->create();

        // Fetch Desa IDs where the related Provinsi's ID is 51
        // Assumes Desa -> Kecamatan -> Kabupaten -> Provinsi relationship chain
        $desaIds = Kelurahan::whereHas('kecamatan.kabupaten.provinsi', function ($query) {
            $query->where('id', 51);
        })->pluck('id');

        // Check if any Desa records were found
        if ($desaIds->isEmpty()) {
            $this->command->warn('No Desa found for provinsi_id 51. Dusun seeding skipped for this condition.');
            return; // Or handle this case as needed
        }

        $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale
        for ($i = 0; $i < 100; $i++) {
            Dusun::create([
                'nama' => $faker->name(),
                'kode' => $faker->regexify('[A-Z0-9]{16}'),
                'aktif' => $faker->boolean(),
                // Assign a random desa_id from the fetched collection
                'desa_id' => $faker->randomElement($desaIds),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }

    public function getAllProgramMarkers()
    {
        $programs = Program::with(['outcome.output.activities.lokasi'])->get();

        $markers = [];

        foreach ($programs as $program) {
            foreach ($program->outcome as $outcome) {
                foreach ($outcome->output as $output) {
                    foreach ($output->activities as $activity) {
                        foreach ($activity->lokasi as $lokasi) {
                            $markers[] = [
                                'program'     => $program->nama,
                                'kegiatan'    => $activity->nama,
                                'latitude'    => $lokasi->lat,
                                'longitude'   => $lokasi->long,
                                'keterangan'  => $lokasi->keterangan ?? '', // jika ada
                            ];
                        }
                    }
                }
            }
        }

        return response()->json($markers);
    }
}
