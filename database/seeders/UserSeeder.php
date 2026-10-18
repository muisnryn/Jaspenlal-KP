<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Staf Keuangan
        User::updateOrCreate(
            ['email' => 'keuangan@test.com'],
            [
                'name'     => 'Staf Keuangan',
                'username' => 'keuangan',
                'password' => Hash::make('password'),
                'role'     => 'keuangan',
            ]
        );

        // Akun Klien
        User::updateOrCreate(
            ['email' => 'klien@test.com'],
            [
                'name'     => 'Muis Nuryana',
                'username' => 'Muis',
                'password' => Hash::make('password'),
                'role'     => 'klien',
            ]
        );

        // Akun Konsultan
        User::updateOrCreate(
            ['email' => 'konsultan@test.com'],
            [
                'name'     => 'Konsultan',
                'username' => 'konsultan',
                'password' => Hash::make('password'),
                'role'     => 'konsultan',
            ]
        );

        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin Sistem',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
