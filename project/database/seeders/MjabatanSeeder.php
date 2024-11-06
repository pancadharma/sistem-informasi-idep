<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MjabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['nama' => 'Manager', 'aktif' => 1],
            ['nama' => 'Supervisor', 'aktif' => 1],
            ['nama' => 'Staff', 'aktif' => 1],
            ['nama' => 'Intern', 'aktif' => 0],
        ];

        foreach ($positions as $position) {
            DB::table('mjabatan')->updateOrInsert(
                ['nama' => $position['nama']], // condition to find existing record
                $position // data to update or insert
            );
        }
    }
}
