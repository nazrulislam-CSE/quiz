<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
            public function run(): void
            {
                $features = [
            [
                'icon' => 'fa-solid fa-book-open',
                'title' => 'অসংখ্য প্রশ্ন ব্যাংক',
                'description' => 'ভার্সিটি, মেডিকেল ও নার্সিং ভর্তি পরীক্ষার বিশাল প্রশ্ন ব্যাংক।',
            ],
            [
                'icon' => 'fa-solid fa-laptop-code',
                'title' => 'স্মার্ট অনলাইন পরীক্ষা',
                'description' => 'বাসায় বসেই রিয়েল টাইম মডেল টেস্ট দেওয়ার সুবিধা।',
            ],
            [
                'icon' => 'fa-solid fa-users',
                'title' => 'অভিজ্ঞ শিক্ষক',
                'description' => 'শীর্ষস্থানীয় শিক্ষকদের তৈরি কনটেন্ট ও প্রশ্ন সেট।',
            ],
            [
                'icon' => 'fa-solid fa-award ',
                'title' => 'রেজাল্ট অ্যানালাইসিস',
                'description' => 'প্রতিটি পরীক্ষার পর বিস্তারিত পারফরম্যান্স রিপোর্ট।',
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['title' => $feature['title']], 
                $feature                       
            );
        }

    }
}
