<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id'             => 1,
                'nama'           => 'RGBComputer Super Admin',
                'username'       => 'admin',
                'jabatan_id'     => 1,
                'email'          => 'rgb@idep.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'description'    => 'RGBComputer Super Admin - Dev',
            ],
        ];

        User::insert($users);
    }
}
