<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;   
use App\Models\Page;   
use App\Models\Menuitem;   
use App\Models\StudyAbroad;   
use App\Models\Education;   
use App\Models\Visa;   
use App\Models\Team;   
use App\Models\Testimonial;   
use App\Models\Setting;   
use App\Models\Menu;   
use App\Models\Slider;   
use App\Models\About;   
use App\Models\Partner;   
use App\Models\Ielt;   
use App\Models\RequestVisa;  
use App\Models\Voucher;  
use App\Models\User;  
use App\Models\Admin;  
use Auth;
 

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle    = 'Dashboard';
        $sections     = Section::latest()->get();   
        $pages        = Page::latest()->get();   
        $menuitems    = Menuitem::latest()->get();   
        $studys       = StudyAbroad::latest()->get();   
        $educations   = Education::latest()->get();   
        $tourist_visas= Visa::where('visa_type',1)->latest()->get();   
        $permit_visas = Visa::where('visa_type',1)->latest()->get();   
        $teams        = Team::latest()->get();   
        $testimonials = Testimonial::latest()->get();   
        $settings     = Setting::latest()->get();   
        $menus        = Menu::latest()->get();   
        $sliders      = Slider::latest()->get();   
        $abouts       = About::latest()->get();   
        $pertners     = Partner::latest()->get();   
        $ielts        = Ielt::latest()->get(); 
        $student_visa_commission = RequestVisa::where('request_visa_type',1)->where('status',4)->sum('commission_amount');
        $work_visa_commission = RequestVisa::where('request_visa_type',2)->where('status',4)->sum('commission_amount');
        $medical_visa_commission = RequestVisa::where('request_visa_type',3)->where('status',4)->sum('commission_amount');
        $tourist_visa_commission = RequestVisa::where('request_visa_type',4)->where('status',4)->sum('commission_amount');  
        $software_visa_commission = RequestVisa::where('request_visa_type',5)->where('status',4)->sum('commission_amount');  
        $advance_payment = Admin::where('id',Auth::guard('admin')->user()->id)->sum('v_advance_payment');  
        return view('admin.dashboard.index', compact('pageTitle', 'sections', 'pages','menuitems','menus','studys','educations','teams', 'tourist_visas','permit_visas', 'testimonials', 'settings','sliders','abouts','pertners','ielts','student_visa_commission','work_visa_commission','medical_visa_commission','tourist_visa_commission','software_visa_commission','advance_payment'));
    }
}
