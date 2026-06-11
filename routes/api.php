<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\User\AuthController as UserAuthController;
use App\Http\Controllers\Api\V1\User\UserController;

use App\Http\Controllers\Api\V1\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::middleware(['check.bk.token'])->prefix('v1')->group(function () {

    // ================= USER ROUTES (Phone OTP Only) =================
    Route::prefix('user')->group(function () {

        // Public routes
        Route::post('register', [UserAuthController::class, 'register']);  // Register
        Route::post('login', [UserAuthController::class, 'login']);        // Send OTP
        Route::post('verify-otp', [UserAuthController::class, 'verifyOtp']); // Verify OTP & Login
        Route::post('resend-otp', [UserAuthController::class, 'resendOtp']); // Resend OTP
        
        // Password Reset via Phone
        Route::post('forgot-password', [UserAuthController::class, 'sendResetLink']);
        Route::post('verify-code', [UserAuthController::class, 'verifyCode']);
        Route::post('reset-password', [UserAuthController::class, 'reset']);

        // Protected routes (requires authentication)
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [UserAuthController::class, 'logout']);
            Route::get('profile', [UserAuthController::class, 'profile']);
            Route::put('profile', [UserAuthController::class, 'updateProfile']);
            Route::post('avatar', [UserAuthController::class, 'uploadAvatar']);
            Route::post('change-password', [UserAuthController::class, 'changePassword']);
            Route::delete('delete-account', [UserAuthController::class, 'deleteAccount']);
            Route::get('dashboard', [UserController::class, 'dashboard']);
        });
    });

    // ================= ADMIN ROUTES =================
    Route::prefix('admin')->group(function () {

        // Public routes
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('forgot-password', [AdminAuthController::class, 'sendResetLink']);
        Route::post('verify-code', [AdminAuthController::class, 'verifyCode']);
        Route::post('reset-password', [AdminAuthController::class, 'reset']);

        // Protected routes
        Route::middleware(['auth:sanctum'])->group(function () {

            Route::post('logout', [AdminAuthController::class, 'logout']);
            Route::get('profile', [AdminAuthController::class, 'profile']);
            Route::put('profile', [AdminAuthController::class, 'updateProfile']);

            // User management
            Route::get('users', [AdminController::class, 'getAllUsers']);
            Route::get('users/{id}', [AdminController::class, 'getUser']);
            Route::put('users/{id}/status', [AdminController::class, 'updateUserStatus']);
            Route::put('users/{id}/wallets', [AdminController::class, 'updateUserWallets']);
            Route::delete('users/{id}', [AdminController::class, 'deleteUser']);

            // Admin management
            Route::get('admins', [AdminController::class, 'getAllAdmins']);
            Route::post('admins', [AdminController::class, 'createAdmin']);
            Route::put('admins/{id}/status', [AdminController::class, 'updateAdminStatus']);
        });
    });
});

// Default route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});