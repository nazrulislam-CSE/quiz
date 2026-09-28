<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ================= DASHBOARD DATA =================
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $teacherCount = Teacher::where('status', 1)->count();
        $studentCount = User::where('status', 1)->count();
        $programCount = Program::where('status', 1)->count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data fetched successfully.',

            'data' => [

                // ================= USER INFO =================
                'user_info' => $user->makeHidden([
                    'password',
                    'show_password',
                ]),

                // ================= WALLETS =================
                'wallets' => [
                    'main_wallet' => $user->main_wallet,
                    'income_wallet' => $user->income_wallet,
                    'withdraw_wallet' => $user->withdraw_wallet,
                    'refer_bonus' => $user->refer_bonus,
                ],

                // ================= COUNTS =================
                'counts' => [
                    'teacher_count' => $teacherCount,
                    'student_count' => $studentCount,
                    'program_count' => $programCount,
                ],
            ],
        ], 200);
    }
}
