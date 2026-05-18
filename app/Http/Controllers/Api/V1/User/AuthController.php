<?php

namespace App\Http\Controllers\Api\V1\User;

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|integer',
            'username' => 'nullable|string|max:255|unique:users,username',
            'company_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'owner_name' => $request->owner_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
            'main_wallet' => 0,
            'income_wallet' => 0,
            'withdraw_wallet' => 0,
            'refer_bonus' => 0,
            'status' => 1,
        ]);

        $token = $user->createToken('user_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    // ================= LOGIN =================
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

        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::guard('web')->user();

        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // Revoke all existing tokens
        $user->tokens()->where('name', 'user_auth_token')->delete();

        $token = $user->createToken('user_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    // ================= FORGOT PASSWORD (SEND CODE) =================
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $code = rand(1000, 9999);

        Cache::put('user_reset_code_' . $user->email, $code, now()->addMinutes(10));

        // Send email with code
        Mail::raw("Your password reset code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset Code');
        });

        return response()->json([
            'success' => true,
            'message' => 'Reset code sent to your email'
        ]);
    }

    // ================= VERIFY CODE =================
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cached = Cache::get('user_reset_code_' . $request->email);

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

    // ================= RESET PASSWORD =================
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'code' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify code again before reset
        $cached = Cache::get('user_reset_code_' . $request->email);

        if (!$cached || $cached != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
        ]);

        Cache::forget('user_reset_code_' . $request->email);

        // Revoke all tokens after password reset
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful. Please login again.'
        ]);
    }

    // ================= GET PROFILE =================
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    // ================= UPDATE PROFILE =================
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|integer|unique:users,phone,' . $user->id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'city_name' => 'nullable|string|max:255',
            'present_address' => 'nullable|string',
            'parmanent_address' => 'nullable|string',
            'date_of_birth' => 'nullable|string',
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'gender' => 'nullable|string',
            'nid_number' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only([
            'full_name', 'phone', 'username', 'city_name', 'present_address',
            'parmanent_address', 'date_of_birth', 'nationality', 'religion',
            'blood_group', 'gender', 'nid_number', 'facebook_url', 'linkedin_url',
            'twitter_url', 'instagram_url'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    // ================= CHANGE PASSWORD =================
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'show_password' => $request->new_password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}