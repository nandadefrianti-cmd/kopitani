<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kopitani.test'],
            [
                'name' => 'Admin KopiTani',
                'password' => env('ADMIN_PASSWORD'),
                'role' => 'admin',
            ]
        );
    }
}