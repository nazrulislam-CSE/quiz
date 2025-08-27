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
        $users = [
            [
                'company_name' => 'ChalkboardBD',
                'username'     => 'chalkboardbd',
                'email'        => 'chalkboardbd@gmail.com',
                'password'     => Hash::make('12345678'),
                'show_password'=> '12345678',
                'phone'        => '01700000000',
                'created_by'   => 'demo',
                'email_verified_at' => now(),
                'status'       => 1,
            ],
            [
                'company_name' => 'Demo',
                'username'     => 'demo',
                'email'        => 'demo@gmail.com',
                'password'     => Hash::make('demo1234'),
                'show_password'=> 'demo1234',
                'phone'        => '01316017328',
                'created_by'   => 'demo',
                'email_verified_at' => now(),
                'status'       => 1,
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
