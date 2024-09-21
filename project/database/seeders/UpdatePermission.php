<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdatePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$admin_permissions = Permission::all();
        $data = [
            [
                'guard_name' => 'web',
            ],
        ];
        Permission::insert($data);
        Role::insert($data);
    }
}
