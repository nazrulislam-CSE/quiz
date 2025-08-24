<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\About;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create(
            [
                'title' => 'MCQ Admission',
                'experience_no' => '10',
                'experience_title' => 'Years Of Experience',
                'description' => 'MCQ Admission হলো একটি স্মার্ট অনলাইন প্ল্যাটফর্ম যেখানে ভর্তি পরীক্ষার জন্য প্রয়োজনীয় সকল উপকরণ একসাথে পাওয়া যায়। এখানে রয়েছে বিশাল প্রশ্ন ব্যাংক, রিয়েল টাইম মডেল টেস্ট, অভিজ্ঞ শিক্ষকের তৈরি কনটেন্ট এবং বিস্তারিত রেজাল্ট অ্যানালাইসিস
                .',
                'video_link' => '#',
                'image' => '#',
                'image1' => '#',
                'status' => '1',
                'created_at' => now()
            ],
        );
    }
}
