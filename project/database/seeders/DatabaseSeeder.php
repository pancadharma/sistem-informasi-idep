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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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
