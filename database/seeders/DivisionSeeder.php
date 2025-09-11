<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            ['name_en' => 'Dhaka',      'name_bn' => 'ঢাকা'],
            ['name_en' => 'Chattogram', 'name_bn' => 'চট্টগ্রাম'],
            ['name_en' => 'Rajshahi',   'name_bn' => 'রাজশাহী'],
            ['name_en' => 'Khulna',     'name_bn' => 'খুলনা'],
            ['name_en' => 'Barisal',    'name_bn' => 'বরিশাল'],
            ['name_en' => 'Sylhet',     'name_bn' => 'সিলেট'],
            ['name_en' => 'Rangpur',    'name_bn' => 'রংপুর'],
            ['name_en' => 'Mymensingh', 'name_bn' => 'ময়মনসিংহ'],
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'name_en' => $division['name_en'],
                'name_bn' => $division['name_bn'],
                'status' => 1,
                'url' => Str::slug($division['name_en']), 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}  