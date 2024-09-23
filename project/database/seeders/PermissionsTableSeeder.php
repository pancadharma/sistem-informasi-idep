<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $permissions = [
                ['id' => 1, 'nama' => 'user_management_access'],
                ['id' => 2, 'nama' => 'permission_create'],
                ['id' => 3, 'nama' => 'permission_edit'],
                ['id' => 4, 'nama' => 'permission_show'],
                ['id' => 5, 'nama' => 'permission_delete'],
                ['id' => 6, 'nama' => 'permission_access'],
                ['id' => 7, 'nama' => 'role_create'],
                ['id' => 8, 'nama' => 'role_edit'],
                ['id' => 9, 'nama' => 'role_show'],
                ['id' => 10, 'nama' => 'role_delete'],
                ['id' => 11, 'nama' => 'role_access'],
                ['id' => 12, 'nama' => 'user_create'],
                ['id' => 13, 'nama' => 'user_edit'],
                ['id' => 14, 'nama' => 'user_show'],
                ['id' => 15, 'nama' => 'user_delete'],
                ['id' => 16, 'nama' => 'user_access'],
                ['id' => 17, 'nama' => 'audit_log_show'],
                ['id' => 18, 'nama' => 'audit_log_access'],
                ['id' => 19, 'nama' => 'profile_password_edit'],
                ['id' => 20, 'nama' => 'country_access'],
                ['id' => 21, 'nama' => 'country_edit'],
                ['id' => 22, 'nama' => 'country_show'],
                ['id' => 23, 'nama' => 'country_delete'],
                ['id' => 24, 'nama' => 'provinsi_access'],
                ['id' => 25, 'nama' => 'provinsi_edit'],
                ['id' => 26, 'nama' => 'provinsi_show'],
                ['id' => 27, 'nama' => 'provinsi_delete'],
                ['id' => 28, 'nama' => 'provinsi_create'],
                ['id' => 29, 'nama' => 'kabupaten_access'],
                ['id' => 30, 'nama' => 'kabupaten_create'],
                ['id' => 31, 'nama' => 'kabupaten_edit'],
                ['id' => 32, 'nama' => 'kabupaten_show'],
                ['id' => 33, 'nama' => 'kabupaten_delete'],
                ['id' => 34, 'nama' => 'kecamatan_access'],
                ['id' => 35, 'nama' => 'kecamatan_create'],
                ['id' => 36, 'nama' => 'kecamatan_edit'],
                ['id' => 37, 'nama' => 'kecamatan_show'],
                ['id' => 38, 'nama' => 'kecamatan_delete'],
                ['id' => 39, 'nama' => 'desa_access'],
                ['id' => 40, 'nama' => 'desa_create'],
                ['id' => 41, 'nama' => 'desa_edit'],
                ['id' => 42, 'nama' => 'desa_show'],
                ['id' => 43, 'nama' => 'desa_delete'],
                ['id' => 44, 'nama' => 'dusun_access'],
                ['id' => 45, 'nama' => 'dusun_create'],
                ['id' => 46, 'nama' => 'dusun_edit'],
                ['id' => 47, 'nama' => 'dusun_show'],
                ['id' => 48, 'nama' => 'dusun_delete'],
                ['id' => 49, 'nama' => 'partner_access'],
                ['id' => 50, 'nama' => 'partner_create'],
                ['id' => 51, 'nama' => 'partner_edit'],
                ['id' => 52, 'nama' => 'partner_delete'],
                ['id' => 53, 'nama' => 'jabatan_access'],
                ['id' => 54, 'nama' => 'jabatan_create'],
                ['id' => 55, 'nama' => 'jabatan_edit'],
                ['id' => 56, 'nama' => 'jabatan_delete'],
            ];

            Permission::insert($permissions);
        });
    }
}
