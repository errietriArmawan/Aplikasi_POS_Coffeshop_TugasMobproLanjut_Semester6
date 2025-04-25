<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'addres' => 'Jalan Super Admin',
            'contac' => '08345678121',
            'role' => 'admin',
        ]);
    }
}
