<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Slider;
use App\Models\Counter;
use App\Models\About;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
        $counters = Counter::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
    
        $pageTitle = 'Home';
        return view('frontend.index',compact('pageTitle','sliders','abouts','counters'));
    }

}
