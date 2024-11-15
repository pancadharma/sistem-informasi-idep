<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaitanSdgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sdgs = [
            ['nama' => 'No Poverty', 'aktif' => 1],
            ['nama' => 'Zero Hunger', 'aktif' => 1],
            ['nama' => 'Good Health and Well-Being', 'aktif' => 1],
            ['nama' => 'Quality Education', 'aktif' => 1],
            ['nama' => 'Climate Action', 'aktif' => 1],
            ['nama' => 'Peace, Justice, and Strong Institutions', 'aktif' => 0],
        ];

        foreach ($sdgs as $sdg) {
            DB::table('mkaitansdg')->updateOrInsert(
                ['nama' => $sdg['nama']], // condition to find existing record
                $sdg // data to update or insert
            );
        }
    }
}
