<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MjabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mjabatan')->insert([
            ['nama' => 'Manager', 'aktif' => 1],
            ['nama' => 'Supervisor', 'aktif' => 1],
            ['nama' => 'Staff', 'aktif' => 1],
            ['nama' => 'Intern', 'aktif' => 0],
        ]);
    }
}
