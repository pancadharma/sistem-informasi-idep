<?php

namespace Database\Seeders;

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
            PendonorSeeder::class,
            KategoriPendonorSeeder::class,
            JenisBantuanSeeder::class,
            KelompokMarjinalSeeder::class,
            FactoryTargetReinstraSeeder::class,

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