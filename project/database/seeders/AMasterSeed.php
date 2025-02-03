<?php

namespace Database\Seeders;

use DB;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class AMasterSeed extends Seeder
{
    /**
     * Used to seed the database on every environment.
     * This is useful for development and testing.
     */
    public function run(): void
    {
        $this->call([
            // CountryTableSeeder::class,
            // ProvinsiSeeder::class,
            // KabupatenSeeder::class,
            // KecamatanSeeder::class,
            // KelurahanSeeder::class,
            // Update KabupatenSeeder to include this seeder
            UpdateNegaraIdInProvinsiSeeder::class,
            UpdateKabupatenSeeder::class,
            UpdateProvinsiSeeder::class,

            // seed some fake data choosen from below class to fill the database
            // DusunSeederFaker::class, // DusunSeederFaker is a custom seeder that extends DusunSeeder
            KaitanSdgSeeder::class,
            PeranSeeder::class,
            KelompokMarjinalSeeder::class,
            FactoryPartnerSeeder::class,
            FactoryTargetReinstraSeeder::class,
            FakerKategoriLokasiKegiatan::class,
            SatuanFakerSeeder::class,
            SektorSeederData::class,
            JenisBantuanSeeder::class,
            KategoriPendonorSeeder::class, //data masih dummy



        ]);
    }
}