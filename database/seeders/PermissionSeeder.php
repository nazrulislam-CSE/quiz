<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert(
            [
                // --------------------------
                [
                    'name' => 'access-all',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // --------------------------
                [
                    'name' => 'settings',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // --------------------------
            ]
        );
    }
}
