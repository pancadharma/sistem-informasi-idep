<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Database\Factories\SatuanProviderFactory;
use DB;

class SatuanFakerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new SatuanProviderFactory($faker));
        foreach (range(1, 50) as $index) {
            DB::table('msatuan')->insert([
                'nama' => $faker->unitName,
                'aktif' => $faker->boolean,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}
