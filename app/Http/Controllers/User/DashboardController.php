<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Voucher;  

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = "Dashboard";
     
        return view('user.dashborad.index', compact('pageTitle',));
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toast('Staff logout successfully!', 'success');
        return redirect('/login');
    } // End Method
}
