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
use App\Http\Controllers\Api\V1\User\Wallet\TransactionHistoryController;
use App\Http\Controllers\Api\V1\User\Wallet\WithdrawController;
use App\Http\Controllers\Api\V1\User\Wallet\BalanceTransferController;
use App\Http\Controllers\Api\V1\User\OnlineQuiz\OnlineQuizController;
use App\Http\Controllers\Api\V1\User\Payment\EPSExampleController;
use App\Http\Controllers\Api\V1\User\Rank\RankController;
use App\Http\Controllers\Api\V1\User\Rank\RewardController;
use App\Http\Controllers\Api\V1\User\Banner\BannerController;
use App\Http\Controllers\Api\V1\User\Recharge\RechargeController;
use App\Http\Controllers\Api\V1\User\Study\StudyCenterController;
use App\Http\Controllers\Api\V1\User\Study\CoursePurchaseController;

use App\Http\Controllers\Api\V1\User\AdmissionInfoController;

use App\Http\Controllers\Api\V1\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::get('/eps/success', [BalanceRequestController::class, 'epsSuccess'])->name('payment.eps.success');
Route::get('/eps/fail', [BalanceRequestController::class, 'epsFail'])->name('payment.eps.fail');
Route::get('/eps/cancel', [BalanceRequestController::class, 'epsCancel'])->name('payment.eps.cancel');

/* ============================================================
   EPS Callback (Public — no auth, EPS hits these)
============================================================ */
Route::prefix('study-center/payment')->group(function () {
    Route::match(['get', 'post'], '/success', [CoursePurchaseController::class, 'paymentSuccess'])
         ->name('study.center.payment.success');
    Route::match(['get', 'post'], '/fail',    [CoursePurchaseController::class, 'paymentFail'])
         ->name('study.center.payment.fail');
    Route::match(['get', 'post'], '/cancel',  [CoursePurchaseController::class, 'paymentCancel'])
         ->name('study.center.payment.cancel');
});


Route::middleware(['check.bk.token'])->prefix('v1')->group(function () {

    // ================= USER ROUTES (Phone OTP Only) =================
    Route::prefix('user')->group(function () {

        // Public routes
        Route::post('register', [UserAuthController::class, 'register']);       // Register
        Route::post('login', [UserAuthController::class, 'login']);             // Send OTP
        Route::post('verify-otp', [UserAuthController::class, 'verifyOtp']);    // Verify OTP & Login
        Route::post('resend-otp', [UserAuthController::class, 'resendOtp']);    // Resend OTP
        Route::post('/login-password', [UserAuthController::class, 'passwordLogin']); // Without Send Otp Login

        // Google Login
        Route::post('google-login', [UserAuthController::class, 'googleLogin']);

        // Password Reset via Phone
        Route::post('forgot-password', [UserAuthController::class, 'sendResetLink']);
        Route::post('verify-code', [UserAuthController::class, 'verifyCode']);
        Route::post('reset-password', [UserAuthController::class, 'reset']);

        // Protected routes (requires authentication)
        Route::middleware(['auth:sanctum'])->group(function () {

            /* ================= AUTH / PROFILE ================= */
            Route::post('logout', [UserAuthController::class, 'logout']);
            Route::get('profile', [UserAuthController::class, 'profile']);
            Route::post('profile', [UserAuthController::class, 'updateProfile']);
            Route::post('avatar', [UserAuthController::class, 'uploadAvatar']);
            Route::post('change-password', [UserAuthController::class, 'changePassword']);
            Route::delete('delete-account', [UserAuthController::class, 'deleteAccount']);
            Route::get('dashboard', [UserController::class, 'dashboard']);

            /* ================= EXAM ================= */
            Route::get('/admissions', [AdmissionController::class, 'index']);
            Route::get('/departments', [DepartmentController::class, 'index']);
            Route::get('/subjects', [SubjectController::class, 'index']);
            Route::get('/topics', [TopicController::class, 'index']);
            Route::get('/mcqs', [McqController::class, 'index']);
            Route::get('/mcqs/{id}', [McqController::class, 'show']);
            Route::get('/mcq-answers', [McqAnswerController::class, 'index']);

            Route::get('/exam/data', [ExamController::class, 'getExamData']);
            Route::post('/exam/submit', [ExamController::class, 'submitExam']);
            Route::get('/exam/result/{id}', [ExamController::class, 'viewExamResult']);
            Route::get('/exam/reports', [ExamController::class, 'getExamReports']);

            /* ================= WALLET ================= */
            Route::post('/balance-request', [BalanceRequestController::class, 'store']);
            Route::get('/balance-request', [BalanceRequestController::class, 'index']);
            Route::get('/referrals', [ReferController::class, 'index']);
            Route::get('/generations', [GenerationController::class, 'index']);
            Route::get('/transactions', [TransactionHistoryController::class, 'transactionHistory']);

            Route::get('/withdraws', [WithdrawController::class, 'index']);
            Route::post('/withdraw', [WithdrawController::class, 'store']);

            Route::get('/balance-transfers', [BalanceTransferController::class, 'index']);
            Route::post('/balance-transfer', [BalanceTransferController::class, 'store']);

            /* ================= ONLINE QUIZ ================= */
            Route::get('/online-quizzes', [OnlineQuizController::class, 'index']);
            Route::get('/online-quizzes/{id}', [OnlineQuizController::class, 'onlineExam']);
            Route::post('/online-quizzes/submit', [OnlineQuizController::class, 'submitExam']);
            Route::get('/online-quizzes/{quiz_id}/result', [OnlineQuizController::class, 'result']);

            /* ================= ADMISSION INFO ================= */
            Route::get('admission-info', [AdmissionInfoController::class, 'index']);

            /* ================= EPS PAYMENT ================= */
            Route::post('/eps/initialize', [BalanceRequestController::class, 'initializeEpsPayment']);

            /* ================= RANK & REWARD ================= */
            Route::get('/rank', [RankController::class, 'rank'])->name('rank');
            Route::get('/reward', [RewardController::class, 'reward'])->name('reward');

            /* ================= BANNER ================= */
            Route::get('/banners', [BannerController::class, 'index']);

            /* ================= RECHARGE ================= */
            Route::post('recharge', [RechargeController::class, 'store']);
            Route::get('recharge-history', [RechargeController::class, 'rechargeHistory']);

            /* ============================================================
               STUDY CENTER (Course List + Purchase + Voucher)
            ============================================================ */
            Route::prefix('study-center')->group(function () {

                // Course list
                Route::get('/', [StudyCenterController::class, 'index'])
                     ->name('study.center');

                // Course details
                Route::get('/course/{slug}', [StudyCenterController::class, 'show'])
                     ->name('study.center.course.show');

                // Purchase (Self + Gift)
                Route::post('/course/{course}/buy', [CoursePurchaseController::class, 'buy'])
                     ->name('study.center.course.buy');

                // My courses (my + gifted)
                Route::get('/my-courses', [CoursePurchaseController::class, 'myCourses'])
                     ->name('study.center.my.courses');

                // Order details
                Route::get('/order/{order_no}', [CoursePurchaseController::class, 'orderDetails'])
                     ->name('study.center.order.details');

                // Voucher (JSON + web_url for print/download)
                Route::get('/voucher/{order_no}', [CoursePurchaseController::class, 'voucher'])
                     ->name('study.center.order.voucher');
            });
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