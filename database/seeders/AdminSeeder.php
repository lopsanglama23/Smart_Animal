<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'adminlopsang@gmail.com'],
            [
                'name' => 'Lopsang',
                'password' => Hash::make('adminlopsang@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
