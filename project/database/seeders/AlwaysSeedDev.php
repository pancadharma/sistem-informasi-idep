<?php

namespace Database\Seeders;

use DB;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class AlwaysSeedDev extends Seeder
{
    /**
     * Used to seed the database on every environment.
     * This is useful for development and testing.
     */
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            MjabatanSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            UpdatePermission::class,
            KategoriPendonorSeeder::class,
            PendonorSeeder::class,
            JenisBantuanSeeder::class,
            KelompokMarjinalSeeder::class,
            // SatuanFakerSeeder::class,
            // FakerKategoriLokasiKegiatan::class,
            // CountryTableSeeder::class,
            // ProvinsiSeeder::class,
            // KabupatenSeeder::class,
            // KecamatanSeeder::class,
            // KelurahanSeeder::class,
            // KaitanSdgSeeder::class,
            // PeranSeeder::class,
        ]);
    }
}
