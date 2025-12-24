<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Root Admin',
            'email' => 'root@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
    }
}
