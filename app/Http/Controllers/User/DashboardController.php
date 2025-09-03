<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Voucher;  
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Mcq;
use App\Models\BalanceRequest;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = "Dashboard";
        $balance = BalanceRequest::where('user_id',Auth::user()->id)->sum('amount');
        return view('user.dashborad.index', compact(
            'pageTitle',
            'balance',
        ));
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toast('Staff logout successfully!', 'success');
        return redirect('/login');
    } // End Method
}
