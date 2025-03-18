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
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->nama, 0, 5) != 'user_' && substr($permission->nama, 0, 5) != 'role_' && substr($permission->nama, 0, 11) != 'permission_';
        });
        // Role::findOrFail(2)->permissions()->sync($user_permissions);

        // $admin_permissions = Permission::all();

        // // For admin role (ID 1)
        // Role::findOrFail(1)->permissions()->sync(
        //     $admin_permissions->pluck('id')->mapWithKeys(function ($id) {
        //         return [$id => ['updated_at' => now()]];
        //     })->toArray()
        // );

        // For user role (assuming ID 2)
        // $user_permissions = $admin_permissions->filter(function ($permission) {
        //     return substr($permission->nama, 0, 5) != 'user_' &&
        //         substr($permission->nama, 0, 5) != 'role_' &&
        //         substr($permission->nama, 0, 11) != 'permission_';
        // });

        Role::findOrFail(2)->permissions()->sync(
            $user_permissions->pluck('id')->mapWithKeys(function ($id) {
                return [$id => ['updated_at' => now()]];
            })->toArray()
        );
    }
}
