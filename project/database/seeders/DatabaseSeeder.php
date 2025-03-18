<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // run this first to seed basic table with data

        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            MjabatanSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            UpdatePermission::class,
            CountryTableSeeder::class,
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            KelurahanSeeder::class,
            KaitanSdgSeeder::class,
            PeranSeeder::class,
        ]);
    }
}