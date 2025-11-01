<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Permission::all()->pluck('id');

        // Super Admin gets all permissions
        Role::findOrFail(1)->permissions()->sync(
            $allPermissions->mapWithKeys(fn($id) => [$id => ['updated_at' => now()]])->toArray()
        );

        // Administrator also gets all permissions (same as Super Admin)
        Role::findOrFail(2)->permissions()->sync(
            $allPermissions->mapWithKeys(fn($id) => [$id => ['updated_at' => now()]])->toArray()
        );
    }
}
