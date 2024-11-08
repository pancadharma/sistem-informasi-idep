<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nama' => 'Project Manager', 'aktif' => 1],
            ['nama' => 'Penanggung Jawab Project', 'aktif' => 1],
            ['nama' => 'Staff', 'aktif' => 0],
        ];

        foreach ($roles as $role) {
            DB::table('mperan')->updateOrInsert(
                ['nama' => $role['nama']], // condition to find existing record
                $role // data to update or insert
            );
        }
    }
}
