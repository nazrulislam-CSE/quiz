<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\User\AuthController as UserAuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\User\Exam\AdmissionController;
use App\Http\Controllers\Api\V1\User\Exam\DepartmentController;
use App\Http\Controllers\Api\V1\User\Exam\SubjectController;
use App\Http\Controllers\Api\V1\User\Exam\TopicController;
use App\Http\Controllers\Api\V1\User\Exam\McqController;
use App\Http\Controllers\Api\V1\User\Exam\McqAnswerController;
use App\Http\Controllers\Api\V1\User\Exam\ExamController;
use App\Http\Controllers\Api\V1\User\Wallet\BalanceRequestController;
use App\Http\Controllers\Api\V1\User\Wallet\ReferController;
use App\Http\Controllers\Api\V1\User\Wallet\GenerationController;
use App\Http\Controllers\Api\V1\User\Wallet\TransactionController;
use App\Http\Controllers\Api\V1\User\Wallet\WithdrawController;

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
        Route::post('/login-password', [UserAuthController::class, 'passwordLogin']); // Without Send Otp Login

        // Google Login
        Route::post('google-login', [UserAuthController::class, 'googleLogin']);
        
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

            // Get admission list (active only)
            Route::get('/admissions', [AdmissionController::class, 'index']);
            // Get departments list
            Route::get('/departments', [DepartmentController::class, 'index']);
            // Get subjects list
            Route::get('/subjects', [SubjectController::class, 'index']);
            // Get topis list
            Route::get('/topics', [TopicController::class, 'index']);
            // Get mcqs list
            Route::get('/mcqs', [McqController::class, 'index']);
            Route::get('/mcqs/{id}', [McqController::class, 'show']);

            // Get mcqs answers list
            Route::get('/mcq-answers', [McqAnswerController::class, 'index']);

            // Exam routes
            Route::get('/exam/data', [ExamController::class, 'getExamData']);
            Route::post('/exam/submit', [ExamController::class, 'submitExam']);
            Route::get('/exam/result/{id}', [ExamController::class, 'viewExamResult']);
            Route::get('/exam/reports', [ExamController::class, 'getExamReports']);

            // Balance Request
            Route::post('/balance-request', [BalanceRequestController::class, 'store']);
            Route::get('/balance-request', [BalanceRequestController::class, 'index']);

            // Refer List
            Route::get('/referrals', [ReferController::class, 'index']);

            // Generations List
            Route::get('/generations', [GenerationController::class, 'index']);

            // Transactions History
            Route::get('/transactions', [TransactionController::class, 'index']);

            // Withdraw
            Route::get('/withdraws', [WithdrawController::class, 'index']);
            Route::post('/withdraw', [WithdrawController::class, 'store']);
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