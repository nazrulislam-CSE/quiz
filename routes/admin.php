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
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuBuilderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FeatureController;



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

    /* ============> Categories <=========== */
    Route::prefix('categories')->group(function () {
        Route::get('/index', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/show/{id}', [CategoryController::class,'show'])->name('category.show');

    });

    /* ===========> Manage Menu Builder <========== */
    Route::group(['prefix'=>'menus'], function(){        
        // Route::get('/menu/builder', MenuBuilder::class)->name('menu.builder.create')
        Route::get('/manage/menus/{id?}', [MenuBuilderController::class, 'index'])->name('menuBuilder');
        Route::post('store/menu', [MenuBuilderController::class, 'store'])->name('menu.store');
        Route::get('delete/menuitem/{id}', [MenuBuilderController::class, 'deleteMenuItem'])->name('deleteMenuItem');
        Route::get('create/menu', [MenuBuilderController::class, 'createMenu'])->name('createMenu');
        Route::get('update/menu', [MenuBuilderController::class, 'updateMenu'])->name('updateMenu');
        Route::get('add/item/menu', [MenuBuilderController::class, 'addItemToMenu'])->name('addItemToMenu');
        Route::post('update/menuitem/{id}', [MenuBuilderController::class, 'updateMenuItem'])->name('updateMenuItem');
        Route::get('delete/menu/{id}', [MenuBuilderController::class, 'destroy'])->name('deleteMenu');
    });

    /* ============> Pages <=========== */
    Route::prefix('pages')->group(function () {
        Route::get('/index', [PageController::class, 'index'])->name('page.index');
        Route::get('/create', [PageController::class, 'create'])->name('page.create');
        Route::post('/store', [PageController::class, 'store'])->name('page.store');
        Route::get('/edit/{id}', [PageController::class, 'edit'])->name('page.edit');
        Route::post('/update/{id}', [PageController::class, 'update'])->name('page.update');
        Route::get('/delete/{id}', [PageController::class, 'destroy'])->name('page.delete');
        Route::get('/show/{id}', [PageController::class,'show'])->name('page.show');

    });

    /* ============> Manage Slider   <=========== */
    Route::prefix('slider')->group(function () {
        Route::get('/index', [SliderController::class, 'index'])->name('slider.index');
        Route::get('/create', [SliderController::class, 'create'])->name('slider.create');
        Route::post('/store', [SliderController::class, 'store'])->name('slider.store');
        Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
        Route::post('/update/{id}', [SliderController::class, 'update'])->name('slider.update');
        Route::get('/delete/{id}', [SliderController::class, 'destroy'])->name('slider.delete');
        Route::get('/show/{id}', [SliderController::class,'show'])->name('slider.show');

    });

    /* ============> Manage Counter   <=========== */
    Route::prefix('counters')->group(function () {
        Route::get('/index', [CounterController::class, 'index'])->name('counter.index');
        Route::get('/create', [CounterController::class, 'create'])->name('counter.create');
        Route::post('/store', [CounterController::class, 'store'])->name('counter.store');
        Route::get('/edit/{id}', [CounterController::class, 'edit'])->name('counter.edit');
        Route::post('/update/{id}', [CounterController::class, 'update'])->name('counter.update');
        Route::get('/delete/{id}', [CounterController::class, 'destroy'])->name('counter.delete');
        Route::get('/show/{id}', [CounterController::class,'show'])->name('counter.show');

    });

    /* ============> About <=========== */
    Route::group(['prefix'=>'abouts'], function(){   
        Route::get('/index', [AboutController::class, 'index'])->name('about.index');
        // Route::get('/create', [AboutController::class, 'create'])->name('about.create');
        // Route::post('/store', [AboutController::class, 'store'])->name('about.store');
        Route::get('/edit/{id}', [AboutController::class, 'edit'])->name('about.edit');
        Route::post('/update/{id}', [AboutController::class, 'update'])->name('about.update');
        Route::get('/delete/{id}', [AboutController::class, 'destroy'])->name('about.delete');
        Route::get('/show/{id}', [AboutController::class,'show'])->name('about.show');

    });

    /* ============> Manage Features   <=========== */
    Route::prefix('features')->group(function () {
        Route::get('/index', [FeatureController::class, 'index'])->name('feature.index');
        Route::get('/create', [FeatureController::class, 'create'])->name('feature.create');
        Route::post('/store', [FeatureController::class, 'store'])->name('feature.store');
        Route::get('/edit/{id}', [FeatureController::class, 'edit'])->name('feature.edit');
        Route::post('/update/{id}', [FeatureController::class, 'update'])->name('feature.update');
        Route::get('/delete/{id}', [FeatureController::class, 'destroy'])->name('feature.delete');
        Route::get('/show/{id}', [FeatureController::class,'show'])->name('feature.show');

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
        Route::post('/mcq/delete-question', [McqController::class, 'deleteQuestion'])->name('mcq.delete-question');
    });

    /* ============> Teacher <=========== */
    Route::prefix('teacher')->group(function () {
        Route::get('/index', [TeacherController::class, 'index'])->name('teacher.index');
        Route::get('/create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/store', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('/edit/{id}', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::post('/update/{id}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::get('/delete/{id}', [TeacherController::class, 'destroy'])->name('teacher.delete');
        Route::get('/show/{id}', [TeacherController::class,'show'])->name('teacher.show');

    });

    /* ============> Student <=========== */
    Route::prefix('student')->group(function () {
        Route::get('/index', [StudentController::class, 'index'])->name('student.index');
        Route::get('/create', [StudentController::class, 'create'])->name('student.create');
        Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
        Route::post('/update/{id}', [StudentController::class, 'update'])->name('student.update');
        Route::get('/delete/{id}', [StudentController::class, 'destroy'])->name('student.delete');
        Route::get('/show/{id}', [StudentController::class,'show'])->name('student.show');

    });
    
});
