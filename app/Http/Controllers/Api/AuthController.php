<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',

            // OPTIONAL FIELDS (from migration)
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
        ]);

        $user = User::create([
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'owner_name' => $request->owner_name,
            'username' => $request->username,

            'password' => Hash::make($request->password),
            'show_password' => $request->password,

            // default values from migration
            'main_wallet' => 0,
            'income_wallet' => 0,
            'withdraw_wallet' => 0,
            'refer_bonus' => 0,
            'status' => 1,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // OPTIONAL: check status
        if ($user->status == 0) {
            return response()->json([
                'message' => 'Account is inactive'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // ================= FORGOT PASSWORD (CODE SEND) =================
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::where('email', $request->email)->first();

        $code = rand(1000, 9999);

        Cache::put('reset_code_' . $user->email, $code, now()->addMinutes(10));

        Mail::raw("Your reset code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset Code');
        });

        return response()->json([
            'message' => 'Reset code sent to email'
        ]);
    }

    // ================= VERIFY CODE =================
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
        ]);

        $cached = Cache::get('reset_code_' . $request->email);

        if (!$cached || $cached != $request->code) {
            return response()->json([
                'message' => 'Invalid or expired code'
            ], 400);
        }

        return response()->json([
            'message' => 'Code verified successfully',
            'email' => $request->email
        ]);
    }

    // ================= RESET PASSWORD =================
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
        ]);

        Cache::forget('reset_code_' . $request->email);

        return response()->json([
            'message' => 'Password reset successful'
        ]);
    }

    // ================= USERS LIST =================
    public function users(Request $request)
    {
        $users = User::latest()->get();

        return response()->json([
            'message' => 'Users list fetched successfully',
            'data' => $users
        ]);
    }
}