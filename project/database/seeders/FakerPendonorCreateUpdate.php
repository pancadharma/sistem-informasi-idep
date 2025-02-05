<?php

namespace Database\Seeders;

use App\Models\MPendonor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakerPendonorCreateUpdate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create me an update or create a random data of pendonor
        $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

        for ($i = 0; $i < 100; $i++) {
            MPendonor::create([
                'mpendonorkategori_id' => $faker->numberBetween(1, 4),
                'nama' => $faker->name(),
                'pic' => $faker->name(),
                'email' => $faker->email(),
                'phone' => $faker->phoneNumber(),
                'aktif' => $faker->boolean(),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }

    }
}