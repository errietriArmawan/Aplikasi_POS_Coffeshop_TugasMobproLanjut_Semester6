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
            'address' => 'Jalan Super Admin', // <-- diperbaiki
            'contact' => '08345678121',       // <-- diperbaiki
            'role' => 'admin',
        ]);
    }
}
