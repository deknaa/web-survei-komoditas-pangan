<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Kepala Dinas',
            'email' => 'kepaladinas@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('dinas123'),
            'role' => 'eksekutif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
