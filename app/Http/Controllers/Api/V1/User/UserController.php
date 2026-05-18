<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ================= DASHBOARD DATA =================
    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user_info' => $user,
                'wallets' => [
                    'main_wallet' => $user->main_wallet,
                    'income_wallet' => $user->income_wallet,
                    'withdraw_wallet' => $user->withdraw_wallet,
                    'refer_bonus' => $user->refer_bonus,
                ]
            ]
        ]);
    }
}