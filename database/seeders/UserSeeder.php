<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert(
            [
                [
                    'company_name' => 'Demo',
                    'username' => 'demo',
                    'email' => 'demo@gmail.com',
                    'password' => Hash::make('demo1234'),
                    'show_password' => 'demo1234',
                    'phone' => '01316017328',
                    'created_by' => 'demo',
                    'email_verified_at' => now(),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
