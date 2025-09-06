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
        $balance = BalanceRequest::where('user_id', Auth::id())->where('status', 'approved')->sum('amount');
        
        $user = Auth::user();

        // Total balance (approved balance requests)
        $balance = BalanceRequest::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->sum('amount');

        // Commission amounts from users table
        $directIncome   = $user->direct_commission;
        $firstGenIncome = $user->first_gen_commission;
        $secondGenIncome = $user->second_gen_commission;
        $dIncome = $user->visa_amount;

        return view('user.dashborad.index', compact(
            'pageTitle',
            'balance',
            'directIncome',
            'firstGenIncome',
            'secondGenIncome',
            'dIncome',
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
