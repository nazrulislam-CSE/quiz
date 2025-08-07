<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\StaffController;
use App\Http\Controllers\User\ClientController;
use App\Http\Controllers\User\SupplierController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\ReceiveController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\ApplicantController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\RequestVisaController;
use App\Http\Controllers\User\AgentWithdrawController;
use App\Http\Controllers\User\AgentVarsityController;
use App\Http\Controllers\User\AgentPassportController;
use App\Http\Controllers\User\AgentVoucherController;
use App\Http\Controllers\User\AgentRefundController;
use App\Http\Controllers\User\AgentNoticeController;
use App\Http\Controllers\User\AgentSupplierController;
use App\Http\Controllers\User\AgentSupplierInvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('logout', [DashboardController::class, 'logout'])->name('user.logout');

    // Staff routes
    // Route::prefix('staffs')->as('staff.')->group(function () {
    //     Route::resource('/', StaffController::class);
    // });

    // Client routes
    // Route::prefix('client')->as('client.')->group(function () {
    //     Route::resource('/', ClientController::class);
    // });

    // // Supplier routes
    // Route::prefix('supplier')->as('supplier.')->group(function () {
    //     Route::resource('/', SupplierController::class);
    // });

    // // Account routes
    // Route::prefix('account')->as('account.')->group(function () {
    //     Route::resource('/', AccountController::class);
    // });

    // // Account routes
    // Route::prefix('receive')->as('receive.')->group(function () {
    //     Route::resource('/', ReceiveController::class);
    // });

    //  // Account routes
    //  Route::prefix('expense')->as('expense.')->group(function () {
    //     Route::resource('/', ExpenseController::class);
    // });

    //  // Account routes

  
    /* ============> Configuration <=========== */
    Route::group(['prefix'=>'settings'], function(){      
        Route::get('/profile/view', [SettingController::class, 'profileView'])->name('profile.view');     
        Route::post('/profile/update', [SettingController::class, 'profileUpdate'])->name('profile.update');     
        Route::get('/password/change', [SettingController::class, 'passwordChange'])->name('password.change');   
        Route::post('/password/update', [SettingController::class, 'passwordUpdate'])->name('password.update');   
    });


});
