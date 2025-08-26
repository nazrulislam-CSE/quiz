<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admission;
use Carbon\Carbon;

class AdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $data = [
            ['name' => 'ভর্তি', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'মেডিকেল', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'নার্সিং', 'image' => 'mcq.png','type' => '2'],
            ['name' => 'জাতীয় বিশ্ববিদ্যালয়', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'ডিপ্লোমা ইঞ্জিনিয়ার', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'বিসিএস প্রিলি', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'ব্যাংক জব', 'image' => 'mcq.png','type' => '2'], 
            ['name' => 'প্রাইমারি জব', 'image' => 'mcq.png','type' => '2'],
            ['name' => 'শিক্ষক নিবন্ধন', 'image' => 'mcq.png','type' => '2'], 

            // নিচে আগেরগুলা type = 1 থাকবে
            ['name' => 'পেপার ফাইনাল পরীক্ষা', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'ফাইনাল মডেল টেস্ট পরীক্ষা', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'চার্ব স্পেশাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'রবি স্পেশাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'GST স্পেশাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'ডিপ্লোমা ফাইনাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'মেডিকেল ফাইনাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'নার্সিং ফাইনাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
            ['name' => 'NU ফাইনাল মডেল টেস্ট', 'image' => 'mcq.png','type' => '1'],
        ];


        foreach ($data as $item) {
            Admission::updateOrCreate(
                ['name' => $item['name']], // Match by name
                [
                    'image'       => $item['image'],
                    'status'      => 1,
                    'created_by'  => 1, // change if needed
                    'updated_by'  => 1,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );
        }
    }
}
