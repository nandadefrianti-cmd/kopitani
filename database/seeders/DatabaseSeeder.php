<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kopitani.test'],
            [
                'name' => 'Admin KopiTani',
                'password' => 'KopiTaniAdmin2026!',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petani@kopitest'],
            [
                'name' => 'Petani Kopi',
                'password' => '0987654321',
                'role' => 'petani',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kedai@test'],
            [
                'name' => 'Kedai Kopi',
                'password' => '1234567890',
                'role' => 'kedai',
            ]
        );
    }
}