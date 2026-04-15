<?php

namespace Database\Seeders;

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
            // Define permissions once; IDs are not hardcoded to avoid collisions
            $definitions = [
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
                ['id' => 69, 'nama' => 'program_details_access'],
                ['id' => 70, 'nama' => 'program_details_edit'],
                ['id' => 71, 'nama' => 'program_outcome_access'],
                ['id' => 72, 'nama' => 'program_outcome_create'],
                ['id' => 73, 'nama' => 'program_outcome_edit'],
                ['id' => 74, 'nama' => 'program_outcome_delete'],
                ['id' => 75, 'nama' => 'program_outcome_details_access'],
                ['id' => 76, 'nama' => 'program_outcome_details_edit'],
                ['id' => 77, 'nama' => 'program_outcome_details_delete'],
                ['id' => 78, 'nama' => 'program_outcome_details_show'],
                ['id' => 79, 'nama' => 'program_outcome_details_create'],
                ['id' => 80, 'nama' => 'program_output_create'],
                ['id' => 81, 'nama' => 'program_output_edit'],
                ['id' => 82, 'nama' => 'program_output_delete'],
                ['id' => 83, 'nama' => 'program_output_show'],
                ['id' => 84, 'nama' => 'program_output_access'],
                ['id' => 85, 'nama' => 'program_output_details_access'],
                ['id' => 86, 'nama' => 'program_output_details_edit'],
                ['id' => 87, 'nama' => 'program_output_details_delete'],
                ['id' => 88, 'nama' => 'program_output_details_show'],
                ['id' => 89, 'nama' => 'kegiatan_create'],
                ['id' => 90, 'nama' => 'kegiatan_edit'],
                ['id' => 91, 'nama' => 'kegiatan_delete'],
                ['id' => 92, 'nama' => 'kegiatan_show'],
                ['id' => 93, 'nama' => 'kegiatan_access'],
                ['id' => 94, 'nama' => 'kegiatan_details_access'],
                ['id' => 95, 'nama' => 'kegiatan_details_edit'],
                ['id' => 96, 'nama' => 'kegiatan_details_delete'],
                ['id' => 97, 'nama' => 'kegiatan_details_show'],
                ['id' => 98, 'nama' => 'kegiatan_report'],
                ['id' => 99, 'nama' => 'meals_access'],
                ['id' => 100, 'nama' => 'meals_create'],
                ['id' => 101, 'nama' => 'meals_index'],
                ['id' => 102, 'nama' => 'meals_show'],
                ['id' => 103, 'nama' => 'meals_edit'],
                ['id' => 104, 'nama' => 'beneficiary'],
                ['id' => 105, 'nama' => 'beneficiary_access'],
                ['id' => 106, 'nama' => 'beneficiary_index'],
                ['id' => 107, 'nama' => 'beneficiary_create'],
                ['id' => 108, 'nama' => 'beneficiary_edit'],
                ['id' => 109, 'nama' => 'beneficiary_show'],
                ['id' => 110, 'nama' => 'komponenmodel_access'],
                ['id' => 111, 'nama' => 'komponenmodel_create'],
                ['id' => 112, 'nama' => 'komponenmodel_index'],
                ['id' => 113, 'nama' => 'komponenmodel_show'],
                ['id' => 114, 'nama' => 'komponenmodel_edit'],
                ['id' => 115, 'nama' => 'komponenmodel_delete'],
                ['id' => 116, 'nama' => 'prepostl_access'],
                ['id' => 117, 'nama' => 'prepostl_create'],
                ['id' => 118, 'nama' => 'prepostl_index'],
                ['id' => 119, 'nama' => 'prepostl_show'],
                ['id' => 120, 'nama' => 'prepostl_edit'],
                ['id' => 121, 'nama' => 'prepostl_delete'],
                ['id' => 122, 'nama' => 'program_status_edit'],
                ['id' => 123, 'nama' => 'kegiatan_status_edit'],
                ['id' => 124, 'nama' => 'marjinal_access'],
                ['id' => 125, 'nama' => 'marjinal_edit'],
                ['id' => 126, 'nama' => 'marjinal_show'],
                ['id' => 127, 'nama' => 'marjinal_create'],
                ['id' => 128, 'nama' => 'kategoridonor_access'],
                ['id' => 129, 'nama' => 'kategoridonor_create'],
                ['id' => 130, 'nama' => 'kategoridonor_edit'],
                ['id' => 131, 'nama' => 'kategoridonor_show'],
                ['id' => 132, 'nama' => 'pendonor_access'],
                ['id' => 133, 'nama' => 'pendonor_create'],
                ['id' => 134, 'nama' => 'pendonor_edit'],
                ['id' => 135, 'nama' => 'pendonor_show'],
                ['id' => 136, 'nama' => 'partner_show'],
                ['id' => 137, 'nama' => 'jenisbantuan_access'],
                ['id' => 138, 'nama' => 'jenisbantuan_create'],
                ['id' => 139, 'nama' => 'jenisbantuan_edit'],
                ['id' => 140, 'nama' => 'jenisbantuan_show'],
                ['id' => 141, 'nama' => 'jenisbantuan_delete'],
                ['id' => 142, 'nama' => 'satuan_show'],
                ['id' => 143, 'nama' => 'sdg_access'],
                ['id' => 144, 'nama' => 'sdg_create'],
                ['id' => 145, 'nama' => 'sdg_edit'],
                ['id' => 146, 'nama' => 'sdg_delete'],
                ['id' => 147, 'nama' => 'sdg_show'],
                ['id' => 148, 'nama' => 'target_reinstra_show'],
                ['id' => 149, 'nama' => 'target_progress_access'],
                ['id' => 150, 'nama' => 'target_progress_create'],
                ['id' => 151, 'nama' => 'target_progress_edit'],
                ['id' => 152, 'nama' => 'target_progress_delete'],
                ['id' => 153, 'nama' => 'target_progress_show'],
                ['id' => 154, 'nama' => 'benchmark_access'],
                ['id' => 155, 'nama' => 'benchmark_create'],
                ['id' => 156, 'nama' => 'benchmark_edit'],
                ['id' => 157, 'nama' => 'benchmark_delete'],
                ['id' => 158, 'nama' => 'benchmark_show'],
                ['id' => 159, 'nama' => 'frm_access'],
                ['id' => 160, 'nama' => 'frm_create'],
                ['id' => 161, 'nama' => 'frm_edit'],
                ['id' => 162, 'nama' => 'frm_delete'],
                ['id' => 163, 'nama' => 'frm_show'],
                ['id' => 164, 'nama' => 'laporan_access'],
                ['id' => 165, 'nama' => 'log_access'],
                ['id' => 166, 'nama' => 'timesheet_access'],
                ['id' => 167, 'nama' => 'fill-timesheet'],
                ['id' => 168, 'nama' => 'approve-timesheet'],
                ['id' => 169, 'nama' => 'history-timesheet'],
                ['id' => 170, 'nama' => 'export-timesheet'],
                ['id' => 171, 'nama' => 'timesheet_ubah_status'],
                ['id' => 172, 'nama' => 'admin_timesheet'],
                ['id' => 172, 'nama' => 'kegiatan_view'],
                
                
            ];

            // Use 'nama' as the natural unique key; deduplicate by name
            $names = array_values(array_unique(array_map(function ($row) {
                return $row['nama'];
            }, $definitions)));

            $now = Carbon::now();
            $rows = array_map(function ($name) use ($now) {
                return [
                    'nama' => $name,
                    'aktif' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $names);

            // Idempotent insert/update by 'nama' without requiring a DB unique index
            foreach ($rows as $row) {
                $exists = DB::table('permissions')->where('nama', $row['nama'])->exists();
                if ($exists) {
                    DB::table('permissions')->where('nama', $row['nama'])->update([
                        'aktif' => 1,
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('permissions')->insert($row);
                }
            }

            if (isset($this->command)) {
                $this->command->info('Permissions seeded: ' . count($rows));
            }
        });
    }
}
