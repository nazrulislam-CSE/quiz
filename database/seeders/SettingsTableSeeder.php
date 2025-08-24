<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name'  => 'site_name',
                'value' => 'Speak Up Bd',
            ],
            [
                'name'  => 'site_logo',
                'value' => '',
            ],
            [
                'name'  => 'site_favicon',
                'value' => '',
            ],
            [
                'name'  => 'site_footer_logo',
                'value' => '',
            ],
            [
                'name'  => 'site_contact_logo',
                'value' => '',
            ],
            [
                'name'  => 'site_company_logo',
                'value' => '',
            ],
            [
                'name'  => 'phone',
                'value' => '01841714651',
            ],
            [
                'name'  => 'email',
                'value' => 'mcq@gmail.com',
            ],
            [
                'name'  => 'business_name',
                'value' => 'MCQ',
            ],
            [
                'name'  => 'business_address',
                'value' => 'Mirpur 10,Dhaka',
            ],
            [
                'name'  => 'business_hours',
                'value' => '10:00 - 6:00, Sa - Thu',
            ],
            [
                'name'  => 'copy_right',
                'value' => 'Copy Right MCQ 2025',
            ],
            [
                'name'  => 'developed_by',
                'value' => 'Nazrul Islam Suzon',
            ],
            [
                'name'  => 'developer_link',
                'value' => 'https://speakupbd.com/',
            ],
            [
                'name'  => 'about',
                'value' => 'MCQ Admission হলো একটি স্মার্ট অনলাইন প্ল্যাটফর্ম যেখানে ভর্তি পরীক্ষার জন্য প্রয়োজনীয় সকল উপকরণ একসাথে পাওয়া যায়। এখানে রয়েছে বিশাল প্রশ্ন ব্যাংক, রিয়েল টাইম মডেল টেস্ট, অভিজ্ঞ শিক্ষকের তৈরি কনটেন্ট এবং বিস্তারিত রেজাল্ট অ্যানালাইসিস।
                .',
            ],
            [
                'name' => 'facebook_url',
                'value' => 'https://www.facebook.com/',
            ],
            [
                'name'  => 'messenger_url',
                'value' => 'https://www.messenger.com/',
            ],
            [
                'name'  => 'twitter_url',
                'value' => 'https://www.twitter.com/',
            ],
            [
                'name'  => 'linkedin_url',
                'value' => 'https://www.linkedin.com/',
            ],
            [
                'name' => 'youtube_url',
                'value' => 'https://www.youtube.com/',
            ],
            [
                'name'  => 'instagram_url',
                'value' => 'https://www.instagram.com/',
            ],
            [
                'name'  => 'pinterest_url',
                'value' => 'https://www.pinterest.com/',
            ],
            [
                'name'  => 'whatsapp_url',
                'value' => 'https://www.whatsapp.com/',
            ],
            [
                'name'  => 'meta_title',
                'value' => 'MCQ Admission',
            ],
            [
                'name'  => 'meta_keyword',
                'value' => 'MCQ Admission',
            ],
            [
                'name'  => 'meta_description',
                'value' => 'MCQ Admission Most Popular It Company Website.',
            ],
            [
                'name'  => 'top_banner',
                'value' => '',
            ],
            [
                'name'  => 'top_banner1',
                'value' => '',
            ],
            [
                'name'  => 'middle_banner',
                'value' => '',
            ],
            [
                'name'  => 'middle_banner1',
                'value' => '',
            ],
            // ... add entries for other types
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['name' => $setting['name']], $setting);
        }
    }
}
