<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id'    => 1,
                'nama' => 'Super Admin',
            ],
            [
                'id'    => 2,
                'nama' => 'Administrator',
            ],
            [
                'id'    => 3,
                'nama' => 'Operator',
            ],
            [
                'id'    => 4,
                'nama' => 'Reader',
            ]
        ];

        $limit = 5000;
        $chunks = array_chunk($roles, $limit);
        $startTime = microtime(true);
        foreach ($chunks as $chunk) {
            Role::upsert($chunk, 'id', array_keys($chunk[0]));
            gc_collect_cycles();
            unset($chunk);
            sleep(1);
        }
        $endTime = microtime(true);
        $upsertTime = $endTime - $startTime;
        echo "Roles done in: $upsertTime seconds\n";
    }
}