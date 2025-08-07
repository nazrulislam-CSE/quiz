<?php


use App\Models\Setting;
use App\Models\Pages;

use Illuminate\Support\Collection;

use Rakibhstu\Banglanumber\NumberToBangla;
use Riskihajar\Terbilang\Facades\Terbilang;

if (!function_exists('get_setting')) {
    function get_setting($name)
    {
        return Setting::where('name', $name)->first();
    }
}


// Search By Side All Categories // 
if (!function_exists('get_all_categories')) {
    function get_all_categories()
    {
        $categories = Category::where('status', 1)->latest()->get();
        return $categories;
    }
}
//Footer page
if (!function_exists('get_pages_both_footer')) {
    function get_pages_both_footer()
    {
        return Pages::where('status',1)
                ->where('position',3)
                ->orWhere('position',1)
                ->orderBy('id','ASC')
                ->get();
    }
}

//Header page
if (!function_exists('get_pages_nav_header')) {
    function get_pages_nav_header()
    {
        return Pages::where('status',1)
                ->where('position',3)
                ->orderBy('id','ASC')
                ->get();
    }
}

//Footer page
if (!function_exists('get_footer_banner')) {
    function get_footer_banner()
    {
        return Banner::where('status',1)
                ->where('position',0)
                ->orderBy('id','DESC')
                ->first();
    }
}

function zero($zero)
{
    $value = 6 - strlen($zero);
    if ($value == 5) {
        return '00000';
    } elseif ($value == 4) {
        return '0000';
    } elseif ($value == 3) {
        return '000';
    } elseif ($value == 2) {
        return '00';
    } elseif ($value == 1) {
        return '0';
    }
}

function number2word($number)
{
    $lang = 'en';

    if ($lang == 'en') {
        return Terbilang::make($number) . ' Taka Only';
    }
    if ($lang == 'bn') {
        $numto = new NumberToBangla();
        return $numto->bnMoney($number) . ' মাত্র';
    }
}
function en2bn($number)
{
    $lang = 'en';

    if ($lang == 'en') {
        return number_format($number, 2);
    }
    if ($lang == 'bn') {
        $numto = new NumberToBangla();
        return $numto->bnCommaLakh($number);
    }
}