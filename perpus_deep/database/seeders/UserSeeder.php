<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Admin Perpus',
            'email' => 'admin@perpus.test',
            'password' => Hash::make('admin123'),
            'telepon' => '081234567890',
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Anggota Satu',
            'email' => 'anggota@perpus.test',
            'password' => Hash::make('anggota123'),
            'telepon' => '082345678901',
            'role' => 'anggota',
        ]);
    }
}
