<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mcq;
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

    public function onlineQuiz(Request $request){
        $pageTitle = 'Online Quiz';

        if(!auth()->check()){
            return redirect()->route('login')->with('error','অনুগ্রহ করে লগইন করুন।');
        }
        $quizs = Mcq::latest()->get();
        return view('frontend.exam.online_quiz',compact('pageTitle','quizs'));
    }

    public function onlineExam($id){
        $pageTitle = 'Online Quiz Exam';

        if(!auth()->check()){
            return redirect()->route('login')->with('error','অনুগ্রহ করে লগইন করুন।');
        }
        // quiz check
        $mcq = Mcq::with('answers')->findOrFail($id);
        return view('frontend.exam.online_quiz_exam', compact('pageTitle', 'mcq'));
    }


    

}
