<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RequestVisa;
use App\Models\Voucher;  

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = "Dashboard";
        $student_visa_commission = RequestVisa::where('request_visa_type',1)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $work_visa_commission = RequestVisa::where('request_visa_type',2)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $medical_visa_commission = RequestVisa::where('request_visa_type',3)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $tourist_visa_commission = RequestVisa::where('request_visa_type',4)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $software_visa_commission = RequestVisa::where('request_visa_type',5)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $software_visa_commission = RequestVisa::where('request_visa_type',5)->where('status',4)->where('user_id',Auth::user()->id)->sum('commission_amount');
     
        $pending_student_visa_commission = RequestVisa::where('request_visa_type',1)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $pending_work_visa_commission = RequestVisa::where('request_visa_type',2)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $pending_medical_visa_commission = RequestVisa::where('request_visa_type',3)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $pending_tourist_visa_commission = RequestVisa::where('request_visa_type',4)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $pending_software_visa_commission = RequestVisa::where('request_visa_type',5)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $pending_software_visa_commission = RequestVisa::where('request_visa_type',5)->where('status',1)->where('user_id',Auth::user()->id)->sum('commission_amount');
        $total_pending_commission = $pending_student_visa_commission+$pending_work_visa_commission+$pending_medical_visa_commission+$pending_tourist_visa_commission+$pending_software_visa_commission;
        $advance_payment = User::where('id',Auth::user()->id)->sum('v_advance_payment');  
        return view('user.dashborad.index', compact('pageTitle','student_visa_commission','work_visa_commission','medical_visa_commission','tourist_visa_commission','software_visa_commission','total_pending_commission','advance_payment'));
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toast('Staff logout successfully!', 'success');
        return redirect('/login');
    } // End Method
}
