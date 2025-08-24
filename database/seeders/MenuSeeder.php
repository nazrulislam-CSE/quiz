<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $menus = [
            [
                'name'              => 'Main Menu',
                'slug'              => 'main-menu',
                'type'              => 'megaMenu',
                'menu_source'       => 'category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Middle Menu',
                'slug'              => 'middle-menu',
                'type'              => 'megaMenu',
                'menu_source'       => 'category',
                'status'            => 2,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Footer Menu',
                'slug'              => 'footer-menu',
                'type'              => 'megaMenu',
                'menu_source'       => 'category',
                'status'            => 3,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
