<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Usuario Prueba',
            'email' => 'test@adacecam.com',
            'password' => bcrypt('password123'), // ← Usar bcrypt en lugar de Hash::make
            'email_verified_at' => now(),
            'provider' => 'local',
            'is_active' => true,
        ]);
    }
}
