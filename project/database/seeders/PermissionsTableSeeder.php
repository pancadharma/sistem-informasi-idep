<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Carbon\Carbon;

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
                ['id' => 57, 'nama' => 'target_reinstra_access'],
                ['id' => 58, 'nama' => 'target_reinstra_create'],
                ['id' => 59, 'nama' => 'target_reinstra_edit'],
                ['id' => 60, 'nama' => 'target_reinstra_delete'],
                ['id' => 61, 'nama' => 'satuan_access'],
                ['id' => 62, 'nama' => 'satuan_create'],
                ['id' => 63, 'nama' => 'satuan_edit'],
                ['id' => 64, 'nama' => 'satuan_delete'],
                ['id' => 65, 'nama' => 'program_access'],
                ['id' => 66, 'nama' => 'program_create'],
                ['id' => 67, 'nama' => 'program_edit'],
                ['id' => 68, 'nama' => 'program_delete'],
                ['id' => 68, 'nama' => 'program_details_access'],
                ['id' => 68, 'nama' => 'program_details_edit'],
                ['id' => 69, 'nama' => 'program_outcome_access'],
                ['id' => 70, 'nama' => 'program_outcome_create'],
                ['id' => 71, 'nama' => 'program_outcome_edit'],
                ['id' => 72, 'nama' => 'program_outcome_delete'],
                ['id' => 73, 'nama' => 'program_outcome_details_access'],
                ['id' => 73, 'nama' => 'program_outcome_details_edit'],
                ['id' => 74, 'nama' => 'program_outcome_details_delete'],
                ['id' => 75, 'nama' => 'program_outcome_details_show'],
                ['id' => 76, 'nama' => 'program_outcome_details_create'],
                ['id' => 77, 'nama' => 'program_output_create'],
                ['id' => 78, 'nama' => 'program_output_edit'],
                ['id' => 79, 'nama' => 'program_output_delete'],
                ['id' => 80, 'nama' => 'program_output_show'],
                ['id' => 81, 'nama' => 'program_output_access'],
                ['id' => 82, 'nama' => 'program_output_details_access'],
                ['id' => 82, 'nama' => 'program_output_details_edit'],
                ['id' => 83, 'nama' => 'program_output_details_delete'],
                ['id' => 84, 'nama' => 'program_output_details_show'],
                ['id' => 85, 'nama' => 'kegiatan_create'],
                ['id' => 86, 'nama' => 'kegiatan_edit'],
                ['id' => 87, 'nama' => 'kegiatan_delete'],
                ['id' => 88, 'nama' => 'kegiatan_show'],
                ['id' => 89, 'nama' => 'kegiatan_access'],
                ['id' => 90, 'nama' => 'kegiatan_details_access'],
                ['id' => 90, 'nama' => 'kegiatan_details_edit'],
                ['id' => 91, 'nama' => 'kegiatan_details_delete'],
                ['id' => 92, 'nama' => 'kegiatan_details_show'],
                ['id' => 93, 'nama' => 'kegiatan_report'],
                ['id' => 94, 'nama' => 'meals_access'],
                ['id' => 95, 'nama' => 'meals_create'],
                ['id' => 96, 'nama' => 'meals_index'],
                ['id' => 97, 'nama' => 'meals_show'],
                ['id' => 98, 'nama' => 'meals_edit'],
                ['id' => 99, 'nama' => 'beneficiary'],
                ['id' => 100, 'nama' => 'beneficiary_access'],
                ['id' => 101, 'nama' => 'beneficiary_index'],
                ['id' => 102, 'nama' => 'beneficiary_create'],
                ['id' => 103, 'nama' => 'beneficiary_edit'],
                ['id' => 104, 'nama' => 'beneficiary_show'],
                ['id' => 105, 'nama' => 'komponenmodel_access'],
                ['id' => 106, 'nama' => 'komponenmodel_create'],
                ['id' => 107, 'nama' => 'komponenmodel_index'],
                ['id' => 108, 'nama' => 'komponenmodel_show'],
                ['id' => 109, 'nama' => 'komponenmodel_edit'],
                ['id' => 110, 'nama' => 'komponenmodel_delete'],
                ['id' => 111, 'nama' => 'prepostl_access'],
                ['id' => 112, 'nama' => 'prepostl_create'],
                ['id' => 113, 'nama' => 'prepostl_index'],
                ['id' => 114, 'nama' => 'prepostl_show'],
                ['id' => 115, 'nama' => 'prepostl_edit'],
                ['id' => 116, 'nama' => 'prepostl_delete'],
            ];

            $now = Carbon::now();
            $permissions = array_map(function ($permission) use ($now) {
                return array_merge($permission, [
                    'created_at' => $now,
                    'aktif' => 1,
                    'updated_at' => $now
                ]);
            }, $permissions);

            $limit = 5000;
            $chunks = array_chunk($permissions, $limit);
            $startTime = microtime(true);
            foreach ($chunks as $chunk) {
                Permission::upsert($chunk, 'id', ['nama', 'updated_at']);
                gc_collect_cycles();
                unset($chunk);
                sleep(1);
            }
            $endTime = microtime(true);
            $upsertTime = $endTime - $startTime;
            echo "Permission Seeder done in: $upsertTime seconds\n";
        });
    }
}
