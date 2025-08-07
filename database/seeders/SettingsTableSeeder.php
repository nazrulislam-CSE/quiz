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
                'value' => 'uploads/setting/logo/1702302389images (1).jpg',
            ],
            [
                'name'  => 'site_favicon',
                'value' => 'uploads/setting/favicon/1702302437images.jpg',
            ],
            [
                'name'  => 'site_footer_logo',
                'value' => 'uploads/setting/logo/1702302389template.png',
            ],
            [
                'name'  => 'site_contact_logo',
                'value' => 'uploads/setting/contact/17023023891701841454logo.png',
            ],
            [
                'name'  => 'site_company_logo',
                'value' => 'uploads/setting/company/17023023891701841454logo.png',
            ],
            [
                'name'  => 'phone',
                'value' => '01841714651',
            ],
            [
                'name'  => 'email',
                'value' => 'speakupbd@gmail.com',
            ],
            [
                'name'  => 'business_name',
                'value' => 'Speak Up Bd Company',
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
                'value' => 'Copy Right Speak Up Bd 2024',
            ],
            [
                'name'  => 'developed_by',
                'value' => 'Speak Up Bd',
            ],
            [
                'name'  => 'developer_link',
                'value' => 'https://speakupbd.com/',
            ],
            [
                'name'  => 'about',
                'value' => 'Speak Up BD is a provider of IT consulting and software development services.
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
                'value' => 'Speak Up Bd',
            ],
            [
                'name'  => 'meta_keyword',
                'value' => 'Speak Up Bd',
            ],
            [
                'name'  => 'meta_description',
                'value' => 'Speak Up Bd Most Popular It Company Website.',
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
