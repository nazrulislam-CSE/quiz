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
use App\Models\Generation;
use App\Models\Transaction;
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

        // ✅ Total Refer Bonus (1st + 2nd generation commissions)
        $referBonus = Generation::where('to_user_id', $user->id)->sum('commission');

        // ✅ Total Direct Income (Direct referral commission)
        $directIncome = Transaction::where('user_id', $user->id)
                        ->where('purpose', 'Direct Referral Commission')
                        ->sum('amount');
        $mainWallet = $user->main_wallet;

        return view('user.dashborad.index', compact(
            'pageTitle',
            'mainWallet',
            'referBonus',
            'directIncome',
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
