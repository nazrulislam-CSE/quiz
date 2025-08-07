<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;


Route::get('login', [AdminLoginController::class, 'viewLogin'])->name('login.view');
Route::post('admin-login', [AdminLoginController::class, 'login'])->name('login');
Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::prefix('passwords')->group(function () {
    Route::get('forget-password', [ForgotPasswordController::class, 'getEmail'])->name('forget.password');
    Route::post('forget-password', [ForgotPasswordController::class, 'postEmail'])->name('forget.password.store');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'getPassword'])->name('reset.password');
    Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('reset.password.store');
});
Route::middleware('admin')->group(function () {

    /* ============> Dashboard <=========== */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.home');

    /* ============> Configuration <=========== */
    Route::group(['prefix'=>'settings'], function(){   
        Route::get('/index', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/update', [SettingController::class, 'update'])->name('settings.update');     
        Route::get('/profile/view', [SettingController::class, 'profileView'])->name('profile.view');     
        Route::post('/profile/update', [SettingController::class, 'profileUpdate'])->name('profile.update');     
        Route::get('/password/change', [SettingController::class, 'passwordChange'])->name('password.change');   
        Route::post('/password/update', [SettingController::class, 'passwordUpdate'])->name('password.update');   
    });
    
});
