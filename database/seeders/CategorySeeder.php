<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            // [
            //     'name'              => 'Home',
            //     'slug'              => 'home',
            //     'parent_id'         => null,
            //     'type'              => 1,
            //     'meta_title'        => 'home',
            //     'keywords'          => 'home,category,about',
            //     'meta_description'  => 'this is home category',
            //     'status'            => 1,
            //     'created_at'        => new \DateTime ?: new \DateTime
            // ],
            [
                'name'              => 'Result',
                'slug'              => 'result',
                'parent_id'         => null,
                'type'              => 1,
                'meta_title'        => 'Result',
                'keywords'          => 'Result,category,home',
                'meta_description'  => 'this is Result category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Apply Now',
                'slug'              => 'apply-now',
                'parent_id'         => null,
                'type'              => 1,
                'meta_title'        => 'news',
                'keywords'          => 'news,news,news',
                'meta_description'  => 'this is news category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Be a partner',
                'slug'              => 'be-a-partner',
                'parent_id'         => null,
                'type'              => 1,
                'meta_title'        => 'Be a partner',
                'keywords'          => 'Be a partner,Be a partner,Be a partner',
                'meta_description'  => 'this is Be a partner category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Our Service',
                'slug'              => 'our-service',
                'parent_id'         => null,
                'type'              => 1,
                'meta_title'        => 'Our Service',
                'keywords'          => 'Our Service,Our Service,Our Service',
                'meta_description'  => 'this is Our Service category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
            [
                'name'              => 'Contact Us',
                'slug'              => 'contact-us',
                'parent_id'         => null,
                'type'              => 1,
                'meta_title'        => 'contact-us',
                'keywords'          => 'contact,category,home',
                'meta_description'  => 'this is contact us category',
                'status'            => 1,
                'created_at'        => new \DateTime ?: new \DateTime
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
        

    }
}
