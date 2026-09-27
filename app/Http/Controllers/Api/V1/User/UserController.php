<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Program;

class UserController extends Controller
{
    // ================= DASHBOARD DATA =================
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Dashboard Data
        $teachers = Teacher::where('status', 1)
            ->latest()
            ->get();

        $students = Student::where('status', 1)
            ->latest()
            ->get();

        $programs = Program::where('status', 1)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data fetched successfully.',

            'data' => [

                // ================= USER INFO =================
                'user_info' => $user,

                // ================= WALLETS =================
                'wallets' => [
                    'main_wallet' => $user->main_wallet,
                    'income_wallet' => $user->income_wallet,
                    'withdraw_wallet' => $user->withdraw_wallet,
                    'refer_bonus' => $user->refer_bonus,
                ],

                // ================= COUNTS =================
                'counts' => [
                    'teacher_count' => $teachers->count(),
                    'student_count' => $students->count(),
                    'program_count' => $programs->count(),
                ],

                // ================= DASHBOARD LIST =================
                'teachers' => $teachers,
                'students' => $students,
                'programs' => $programs,
            ]
        ], 200);
    }
}
