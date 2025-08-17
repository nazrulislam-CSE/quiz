<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\McqController;



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

    /* ============> Admission <=========== */
    Route::prefix('admission')->group(function () {
        Route::get('/index', [AdmissionController::class, 'index'])->name('admission.index');
        Route::get('/create', [AdmissionController::class, 'create'])->name('admission.create');
        Route::post('/store', [AdmissionController::class, 'store'])->name('admission.store');
        Route::get('/edit/{id}', [AdmissionController::class, 'edit'])->name('admission.edit');
        Route::post('/update/{id}', [AdmissionController::class, 'update'])->name('admission.update');
        Route::get('/delete/{id}', [AdmissionController::class, 'destroy'])->name('admission.delete');
        Route::get('/show/{id}', [AdmissionController::class,'show'])->name('admission.show');

    });

    /* ============> Department <=========== */
    Route::prefix('department')->group(function () {
        Route::get('/index', [DepartmentController::class, 'index'])->name('department.index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
        Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
        Route::get('/delete/{id}', [DepartmentController::class, 'destroy'])->name('department.delete');
        Route::get('/show/{id}', [DepartmentController::class,'show'])->name('department.show');

    });

    /* ============> Subject <=========== */
    Route::prefix('subject')->group(function () {
        Route::get('/index', [SubjectController::class, 'index'])->name('subject.index');
        Route::get('/create', [SubjectController::class, 'create'])->name('subject.create');
        Route::post('/store', [SubjectController::class, 'store'])->name('subject.store');
        Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
        Route::post('/update/{id}', [SubjectController::class, 'update'])->name('subject.update');
        Route::get('/delete/{id}', [SubjectController::class, 'destroy'])->name('subject.delete');
        Route::get('/show/{id}', [SubjectController::class,'show'])->name('subject.show');
        Route::get('/get-departments/{admission_id}', [SubjectController::class, 'getDepartments'])->name('get.departments');
    });

    /* ============> Topics <=========== */
    Route::prefix('topic')->group(function () {
        Route::get('/index', [TopicController::class, 'index'])->name('topic.index');
        Route::get('/create', [TopicController::class, 'create'])->name('topic.create');
        Route::post('/store', [TopicController::class, 'store'])->name('topic.store');
        Route::get('/edit/{id}', [TopicController::class, 'edit'])->name('topic.edit');
        Route::post('/update/{id}', [TopicController::class, 'update'])->name('topic.update');
        Route::get('/delete/{id}', [TopicController::class, 'destroy'])->name('topic.delete');
        Route::get('/show/{id}', [TopicController::class,'show'])->name('topic.show');
        Route::get('/get-departments/{admission_id}', [TopicController::class, 'getDepartments'])->name('get.departments');
        Route::get('/get-subjects/{department_id}', [TopicController::class, 'getSubjects']);
    });

    /* ============> MCQ <=========== */
    Route::prefix('mcq')->group(function () {
        Route::get('/index', [McqController::class, 'index'])->name('mcq.index');
        Route::get('/create', [McqController::class, 'create'])->name('mcq.create');
        Route::post('/store', [McqController::class, 'store'])->name('mcq.store');
        Route::get('/edit/{id}', [McqController::class, 'edit'])->name('mcq.edit');
        Route::put('/update/{id}', [McqController::class, 'update'])->name('mcq.update');
        Route::get('/delete/{id}', [McqController::class, 'destroy'])->name('mcq.delete');
        Route::get('/show/{id}', [McqController::class,'show'])->name('mcq.show');
        Route::get('/get-departments/{admission_id}', [McqController::class, 'getDepartments'])->name('get.departments');
        Route::get('/get-subjects/{department_id}', [McqController::class, 'getSubjects']);
        Route::get('/get-topics/{subject_id}', [McqController::class, 'getTopics']);
    });
    
});
