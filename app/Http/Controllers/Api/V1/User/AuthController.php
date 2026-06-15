<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Traits\SMSTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use SMSTrait;

    // ================= REGISTER WITH PHONE =================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:11|unique:users,phone',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'nullable|string|max:255|unique:users,username',
            'company_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'refer_by' => 'nullable|string|max:255|exists:users,username', // Referral username
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Process referral if provided
        $referId = null;
        $referUser = null;

        if ($request->filled('refer_by')) {

            $referUser = User::where('username', $request->refer_by)->first();

            if (! $referUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid referral username.',
                ], 400);
            }

        } else {
            // Default referral user
            $referUser = User::where('username', 'chalkboardbd')->first();
        }

        $referId = $referUser?->id;

        // Generate random password for phone-only users
        $randomPassword = Str::random(12);

        $user = User::create([
            'phone' => $request->phone,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'owner_name' => $request->owner_name,
            'username' => $request->username ?? 'user_'.$request->phone,
            'refer_by' => $referId, // Store referrer ID
            'refer_bonus' => 0, // New user doesn't get bonus, referrer gets
            'password' => Hash::make($randomPassword),
            'show_password' => $randomPassword,
            'main_wallet' => 0,
            'income_wallet' => 0,
            'withdraw_wallet' => 0,
            'status' => 1,
        ]);

        // Send welcome SMS
        try {
            $welcomeMessage = "Welcome to ChalkboardBD!\n";
            $welcomeMessage .= "Your account has been created successfully.\n\n";
            $welcomeMessage .= "Login Details:\n";
            $welcomeMessage .= "Phone: {$request->phone}\n";
            $welcomeMessage .= "Password: {$randomPassword}\n\n";

            if ($referUser) {
                $welcomeMessage .= "Referred By: {$referUser->full_name}\n\n";
            }

            $welcomeMessage .= "For security, please change your password after login.\n";
            $welcomeMessage .= "Thank you for joining ChalkboardBD.";

            $this->sendSMS($request->phone, $welcomeMessage);

        } catch (\Exception $e) {
            \Log::error('Welcome SMS failed: '.$e->getMessage());
        }

        // Send welcome email if email provided
        if ($request->email) {
            try {
                Mail::raw("Welcome to ChalkboardBD! Your account has been created successfully.\n\n".
                        ($referUser ? 'You were referred by: '.$referUser->full_name : ''),
                    function ($message) use ($request) {
                        $message->to($request->email)
                            ->subject('Welcome to ChalkboardBD');
                    });
            } catch (\Exception $e) {
                \Log::error('Welcome email failed: '.$e->getMessage());
            }
        }

        $responseData = [
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'username' => $user->username,
            ],
        ];

        // Add referral info in response
        if ($referUser) {
            $responseData['referral'] = [
                'referred_by' => $referUser->username,
                'referred_by_name' => $referUser->full_name,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $referUser ? 'User registered successfully with referral bonus!' : 'User registered successfully. Please login using OTP.',
            'data' => $responseData,
        ], 201);
    }

    // ================= LOGIN - SEND OTP TO PHONE =================
    public function login(Request $request)
    {
        // dd('test');
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone;

        // Check if phone exists
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this phone number. Please register first.',
            ], 404);
        }

        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.',
            ], 403);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache with 5 minutes expiration
        Cache::put('otp_'.$phone, [
            'otp' => $otp,
            'user_id' => $user->id,
            'user_data' => $user->toArray(),
        ], now()->addMinutes(5));

        // Send OTP via SMS using SMSTrait
        try {
            $smsMessage = "Your ChalkboardBD login verification code is: $otp. Valid for 5 minutes.";
            $response = $this->sendSMS($phone, $smsMessage);
            // dd($response);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully to '.$phone,
                    'data' => [
                        'phone' => $phone,
                        'otp' => $otp, // only for testing
                    ],
                ]);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP: '.($response['description'] ?? 'Unknown error'),
                    'error' => $response,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMS sending failed: '.$e->getMessage(),
            ], 500);
        }
    }

    // ================= VERIFY OTP AND COMPLETE LOGIN =================
    public function verifyOtp(Request $request)
    {
        // dd('sadf');
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone;
        $cachedData = Cache::get('otp_'.$phone);

        if (! $cachedData || $cachedData['otp'] != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        $user = User::find($cachedData['user_id']);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.',
            ], 403);
        }

        // Delete OTP from cache
        Cache::forget('otp_'.$phone);

        // Revoke all existing tokens
        $user->tokens()->where('name', 'user_auth_token')->delete();

        // Create new token
        $token = $user->createToken('user_auth_token')->plainTextToken;

        // Update last login time
        $user->update([
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'username' => $user->username,
                    'image' => $user->image ? asset('storage/'.$user->image) : null,
                    'main_wallet' => (float) $user->main_wallet,
                    'income_wallet' => (float) $user->income_wallet,
                    'withdraw_wallet' => (float) $user->withdraw_wallet,
                    'refer_bonus' => (float) $user->refer_bonus,
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    // ================= RESEND OTP =================
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone;
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this phone number',
            ], 404);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Update cache
        Cache::put('otp_'.$phone, [
            'otp' => $otp,
            'user_id' => $user->id,
            'user_data' => $user->toArray(),
        ], now()->addMinutes(5));

        // Send OTP via SMS
        try {
            $smsMessage = "Your ChalkboardBD login verification code is: $otp. Valid for 5 minutes.";
            $response = $this->sendSMS($phone, $smsMessage);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP resent successfully to '.$phone,
                    'data' => [
                        'phone' => $phone,
                        'otp' => $otp, // Remove in production
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resend OTP: '.($response['message'] ?? 'Unknown error'),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMS sending failed: '.$e->getMessage(),
            ], 500);
        }
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    // ================= GET PROFILE =================
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'username' => $user->username,
                'image' => $user->image ? asset('storage/'.$user->image) : null,
                'main_wallet' => (float) $user->main_wallet,
                'income_wallet' => (float) $user->income_wallet,
                'withdraw_wallet' => (float) $user->withdraw_wallet,
                'refer_bonus' => (float) $user->refer_bonus,
                'city_name' => $user->city_name,
                'present_address' => $user->present_address,
                'parmanent_address' => $user->parmanent_address,
                'date_of_birth' => $user->date_of_birth,
                'nationality' => $user->nationality,
                'religion' => $user->religion,
                'blood_group' => $user->blood_group,
                'gender' => $user->gender,
                'nid_number' => $user->nid_number,
                'facebook_url' => $user->facebook_url,
                'linkedin_url' => $user->linkedin_url,
                'twitter_url' => $user->twitter_url,
                'instagram_url' => $user->instagram_url,
                'status' => $user->status,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    // ================= UPDATE PROFILE =================
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'city_name' => 'nullable|string|max:255',
            'present_address' => 'nullable|string',
            'parmanent_address' => 'nullable|string',
            'date_of_birth' => 'nullable|string',
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'nid_number' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($request->only([
            'full_name', 'email', 'username', 'city_name', 'present_address',
            'parmanent_address', 'date_of_birth', 'nationality', 'religion',
            'blood_group', 'gender', 'nid_number', 'facebook_url', 'linkedin_url',
            'twitter_url', 'instagram_url',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'show_password' => $request->new_password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    // ================= UPLOAD AVATAR =================
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->image && $user->image != 'user.png' && file_exists(storage_path('app/public/'.$user->image))) {
            unlink(storage_path('app/public/'.$user->image));
        }

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        $user->update(['image' => $avatarPath]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => asset('storage/'.$avatarPath),
            ],
        ]);
    }

    // ================= DELETE ACCOUNT =================
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        // Log deletion reason
        if ($request->reason) {
            \Log::info('Account deletion requested', [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'reason' => $request->reason,
            ]);
        }

        // Revoke all tokens
        $user->tokens()->delete();

        // Delete user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
        ]);
    }

    // ================= FORGOT PASSWORD (SEND OTP TO PHONE) =================
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|min:11|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();
        $code = rand(100000, 999999);

        Cache::put('user_reset_code_'.$user->phone, [
            'code' => $code,
            'user_id' => $user->id,
        ], now()->addMinutes(10));

        // Send SMS with code
        try {
            $smsMessage = "Your ChalkboardBD password reset code is: $code. Valid for 10 minutes.";
            $response = $this->sendSMS($user->phone, $smsMessage);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {
                return response()->json([
                    'success' => true,
                    'otp' => $code, // 
                    'message' => 'Reset code sent to your phone',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send reset code',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMS sending failed: '.$e->getMessage(),
            ], 500);
        }
    }

    // ================= VERIFY RESET CODE =================
    public function verifyCode(Request $request)
    {
        // dd('test');
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|min:11|exists:users,phone',
            'code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $cached = Cache::get('user_reset_code_'.$request->phone);

        if (! $cached || $cached['code'] != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified successfully',
            'data' => [
                'phone' => $request->phone,
                'code' => $request->code,
            ],
        ]);
    }

    // ================= RESET PASSWORD =================
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|min:11|exists:users,phone',
            'password' => 'required|confirmed|min:8',
            'code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify code again before reset
        $cached = Cache::get('user_reset_code_'.$request->phone);

        if (! $cached || $cached['code'] != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired code',
            ], 400);
        }

        $user = User::where('phone', $request->phone)->first();

        $user->update([
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
        ]);

        Cache::forget('user_reset_code_'.$request->phone);

        // Revoke all tokens after password reset
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful. Please login again.',
        ]);
    }
}
