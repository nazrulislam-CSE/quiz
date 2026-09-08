<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EPSExampleController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\MenuPagesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/api', function () {
    return view('api');
});

Auth::routes();

/* =========> Sart Frontend All Routes <========= */
Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');
Route::get('/online/quiz', [FrontendController::class, 'onlineQuiz'])->name('online.quiz')->middleware('auth');
Route::get('/online/exam/{id}', [FrontendController::class, 'onlineExam'])->name('user.online.quiz.exam')->middleware('auth');
Route::post('/exam/submit', [FrontendController::class, 'submitExam'])->name('exam.submit')->middleware('auth');
Route::get('/exam/result/{quiz_id}', [FrontendController::class, 'result'])->name('exam.result')->middleware('auth');

// page all route
Route::get('/page/{url}', [MenuPagesController::class, 'index'])->name('menu.page');
Route::get('/pages/{page}', [MenuPagesController::class, 'FooterPages'])->name('footer.menu.page');
Route::post('/contact/store', [MenuPagesController::class, 'ContactPages'])->name('contact.store');
Route::post('/search/result', [MenuPagesController::class, 'SearchResult'])->name('result.search');
Route::get('/program/{slug}', [MenuPagesController::class, 'programShow'])->name('program.show');

/* =========> End Frontend All Routes <========== */

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/check-refer/{username}', [RegisterController::class, 'checkRefer'])->name('check.refer');



// EPS Payment
Route::get('/eps-payment',[EPSExampleController::class, 'index'])->name('eps.payment');
Route::post('/eps-payment/initialize',[EPSExampleController::class, 'initializePayment'])->name('eps.payment.initialize');
Route::get('/payment/success',[EPSExampleController::class, 'success'])->name('payment.success');
Route::get('/payment/fail',[EPSExampleController::class, 'fail'])->name('payment.fail');
Route::get('/payment/cancel',[EPSExampleController::class, 'cancel'])->name('payment.cancel');
