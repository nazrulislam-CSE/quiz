<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create(
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '01950594285',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => now()
            ],
            [
                'name' => 'Manger',
                'username' => 'manager',
                'email' => 'manager@gmail.com',
                'phone' => '01950594283',
                'email_verified_at' => now(),
                'password' => Hash::make('manager'),
                'show_password' => 'manager',
                'created_at' => now()
            ],
        );
    }
}
