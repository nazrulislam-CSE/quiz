<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'page_name'              => 'Home',
                'page_title'             => 'Home page',
                'page_slug'              => 'home-page',
                'page_description'       => 'This is Home Page',
                'meta_title'             => 'contact-us',
                'keywords'               => 'home,home,home',
                'meta_description'       => 'this is home page',
                'status'                 => 1,
                'is_default'             => 1,
                'created_by'             => 1,
                'position'               => 3,
                'created_at'             => new \DateTime ?: new \DateTime
            ],
            [
                'page_name'              => 'About Us',
                'page_title'             => 'About Us',
                'page_slug'              => 'about-us',
                'page_description'       => 'This is About Us Page',
                'meta_title'             => 'about-us',
                'keywords'               => 'about-us,about-us,about-us',
                'meta_description'       => 'this is about-us page',
                'status'                 => 1,
                'is_default'             => 1,
                'created_by'             => 1,
                'position'               => 3,
                'created_at'             => new \DateTime ?: new \DateTime
            ],
            [
                'page_name'              => 'Contact Us',
                'page_title'             => 'Contact Us',
                'page_slug'              => 'contact-us',
                'page_description'       => 'This is Contact Us Page',
                'meta_title'             => 'contact-us',
                'keywords'               => 'contact,contact,contact',
                'meta_description'       => 'this is contact us page',
                'status'                 => 1,
                'is_default'             => 1,
                'created_by'             => 1,
                'position'               => 3,
                'created_at'             => new \DateTime ?: new \DateTime
            ],
            [
                'page_name'              => 'Privecy Policy',
                'page_title'             => 'Privecy Policy',
                'page_slug'              => 'privecy-policy',
                'page_description'       => 'This is Privecy Policy Us Page',
                'meta_title'             => 'privecy-policy',
                'keywords'               => 'privecy,privecy,privecy',
                'meta_description'       => 'this is privecy page',
                'status'                 => 1,
                'is_default'             => 1,
                'created_by'             => 1,
                'position'               => 3,
                'created_at'             => new \DateTime ?: new \DateTime
            ],
            [
                'page_name'              => 'Terms & Conditions',
                'page_title'             => 'Terms & Conditions',
                'page_slug'              => 'terms-&-conditions',
                'page_description'       => 'This is Terms & Conditions Page',
                'meta_title'             => 'terms-&-conditions',
                'keywords'               => 'terms-&-conditions,terms-&-conditions,terms-&-conditions',
                'meta_description'       => 'this is terms-&-conditions page',
                'status'                 => 1,
                'is_default'             => 1,
                'created_by'             => 1,
                'position'               => 3,
                'created_at'             => new \DateTime ?: new \DateTime
            ],
            
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
