<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'role' => '1',
                'password' => Hash::make('123456'),
                'created_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'username' => 'user',
                'role' => '0',
                'password' => Hash::make('123456'),
                'created_at' => now(),
            ],
        ]);
    }
}
