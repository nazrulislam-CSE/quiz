<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Slider;
use App\Models\Counter;
use App\Models\About;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admission;
use App\Models\Feature;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
        $counters = Counter::where('status',1)->latest()->get();
        $abouts = About::where('status',1)->latest()->get();
        $teachers = Teacher::where('status',1)->latest()->get();
        $students = Student::where('status',1)->latest()->get();
        $admissions = Admission::where('status',1)->where('type',2)->latest()->get();
        $features = Feature::where('status',1)->latest()->get();
    
        $pageTitle = 'Home';
        return view('frontend.index',compact('pageTitle','sliders','abouts','counters','teachers','students','admissions','features'));
    }

}
