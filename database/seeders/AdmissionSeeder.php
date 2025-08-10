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
            ['name' => 'ভর্তি', 'image' => 'default.png'],
            ['name' => 'মেডিকেল', 'image' => 'default.png'],
            ['name' => 'নার্সিং', 'image' => 'default.png'],
            ['name' => 'জাতীয় বিশ্ববিদ্যালয়', 'image' => 'default.png'],
            ['name' => 'ডিপ্লোমা ইঞ্জিনিয়ার', 'image' => 'default.png'],
            ['name' => 'পেপার ফাইনাল পরীক্ষা', 'image' => 'default.png'],
            ['name' => 'ফাইনাল মডেল টেস্ট পরীক্ষা', 'image' => 'default.png'],
            ['name' => 'চার্ব স্পেশাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'রবি স্পেশাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'GST স্পেশাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'ডিপ্লোমা ফাইনাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'মেডিকেল ফাইনাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'নার্সিং ফাইনাল মডেল টেস্ট', 'image' => 'default.png'],
            ['name' => 'NU ফাইনাল মডেল টেস্ট', 'image' => 'default.png'],
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
