<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'id'    => 1,
                'nama' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'nama' => 'permission_create',
            ],
            [
                'id'    => 3,
                'nama' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'nama' => 'permission_show',
            ],
            [
                'id'    => 5,
                'nama' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'nama' => 'permission_access',
            ],
            [
                'id'    => 7,
                'nama' => 'role_create',
            ],
            [
                'id'    => 8,
                'nama' => 'role_edit',
            ],
            [
                'id'    => 9,
                'nama' => 'role_show',
            ],
            [
                'id'    => 10,
                'nama' => 'role_delete',
            ],
            [
                'id'    => 11,
                'nama' => 'role_access',
            ],
            [
                'id'    => 12,
                'nama' => 'user_create',
            ],
            [
                'id'    => 13,
                'nama' => 'user_edit',
            ],
            [
                'id'    => 14,
                'nama' => 'user_show',
            ],
            [
                'id'    => 15,
                'nama' => 'user_delete',
            ],
            [
                'id'    => 16,
                'nama' => 'user_access',
            ],
            [
                'id'    => 17,
                'nama' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'nama' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'nama' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
