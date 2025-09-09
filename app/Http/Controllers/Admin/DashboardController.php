<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  
use App\Models\Setting;    
use App\Models\User;  
use App\Models\Admin;  
use App\Models\Admission;  
use App\Models\Department;  
use App\Models\BalanceRequest;  
use Auth;
 

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle    = 'Dashboard';  
        $settings     = Setting::latest()->get();     
        $admissions     = Admission::where('status',1)->latest()->get();     
        $departments     = Department::where('status',1)->latest()->get();     
        $users     = User::latest()->get();     
        $activeusers     = User::where('status',1)->latest()->get();     
        $inactiveusers     = User::where('status',0)->latest()->get();     
        $totalBalanceRequest = BalanceRequest::sum('amount');
        $approvedBalanceRequest = BalanceRequest::where('status', 'approve')->sum('amount');

        return view('admin.dashboard.index', compact('pageTitle','settings','admissions','departments','users','activeusers','inactiveusers','totalBalanceRequest','approvedBalanceRequest'));
    }
}
