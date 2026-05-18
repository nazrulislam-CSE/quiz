<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ================= ADMIN LOGIN =================
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($admin->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact super admin.'
            ], 403);
        }

        // Revoke all existing tokens
        $admin->tokens()->where('name', 'admin_auth_token')->delete();

        $token = $admin->createToken('admin_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Admin login successful',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'email' => $admin->email,
                    'phone' => $admin->phone,
                    'image' => $admin->image,
                    'status' => $admin->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    // ================= ADMIN LOGOUT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin logged out successfully'
        ]);
    }

    // ================= ADMIN FORGOT PASSWORD =================
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::where('email', $request->email)->first();
        $code = rand(1000, 9999);

        Cache::put('admin_reset_code_' . $admin->email, $code, now()->addMinutes(10));

        Mail::raw("Your admin password reset code is: $code", function ($message) use ($admin) {
            $message->to($admin->email)
                ->subject('Admin Password Reset Code');
        });

        return response()->json([
            'success' => true,
            'message' => 'Reset code sent to admin email'
        ]);
    }

    // ================= ADMIN VERIFY CODE =================
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
            'code' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cached = Cache::get('admin_reset_code_' . $request->email);

        if (!$cached || $cached != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified successfully',
            'data' => [
                'email' => $request->email
            ]
        ]);
    }

    // ================= ADMIN RESET PASSWORD =================
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|confirmed|min:8',
            'code' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cached = Cache::get('admin_reset_code_' . $request->email);

        if (!$cached || $cached != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code'
            ], 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        $admin->update([
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
        ]);

        Cache::forget('admin_reset_code_' . $request->email);
        $admin->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin password reset successful. Please login again.'
        ]);
    }

    // ================= GET ADMIN PROFILE =================
    public function profile(Request $request)
    {
        $admin = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'address' => $admin->address,
                'image' => $admin->image,
                'status' => $admin->status,
                'v_total_amount' => $admin->v_total_amount,
                'voucher_amount' => $admin->voucher_amount,
                'v_advance_payment' => $admin->v_advance_payment,
            ]
        ]);
    }

    // ================= UPDATE ADMIN PROFILE =================
    public function updateProfile(Request $request)
    {
        $admin = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|integer',
            'address' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin->update($request->only(['name', 'phone', 'address', 'image']));

        return response()->json([
            'success' => true,
            'message' => 'Admin profile updated successfully',
            'data' => $admin
        ]);
    }
}