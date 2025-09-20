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
use App\Http\Controllers\User\ExamController;
use App\Http\Controllers\User\ReferController;
use App\Http\Controllers\User\GenerationController;
use App\Http\Controllers\User\BalanceRequestController;
use App\Http\Controllers\User\WithdrawController;
use App\Http\Controllers\User\BalanceTransferController;
use App\Http\Controllers\User\OnlineQuizController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('user.home');
    Route::get('logout', [DashboardController::class, 'logout'])->name('user.logout');
    Route::get('/mcq/exam', [ExamController::class, 'create'])->name('mcq.exam');
    Route::post('/exam/submit', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam/view/{id}', [ExamController::class, 'examView'])->name('exam.view');
    Route::get('/exam/reports', [ExamController::class, 'reportList'])->name('exam.reports');
    Route::get('/refer-list', [ReferController::class, 'index'])->name('refer.list');
    Route::get('/generation-list', [GenerationController::class, 'index'])->name('generation.list');
    Route::get('/balance-request', [BalanceRequestController::class, 'create'])->name('balance.request');
    Route::post('/balance-request', [BalanceRequestController::class, 'store'])->name('balance.request.store');
    Route::get('/balance-request/report', [BalanceRequestController::class, 'report'])->name('balance.request.report');

    Route::prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/create', [WithdrawController::class, 'create'])->name('create');
        Route::post('/store', [WithdrawController::class, 'store'])->name('store');
        Route::get('/list', [WithdrawController::class, 'index'])->name('list');
    });

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
    
    Route::group(['prefix'=>'transfer'], function(){ 
        Route::get('/balance-transfer', [BalanceTransferController::class, 'index'])->name('balance.transfer.index');
        Route::get('/balance-transfer/create', [BalanceTransferController::class, 'create'])->name('balance.transfer.create');
        Route::post('/balance-transfer/store', [BalanceTransferController::class, 'store'])->name('balance.transfer.store');
    });



});
