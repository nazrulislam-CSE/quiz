<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\OrderController;

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

Auth::routes();

/* =========> Sart Frontend All Routes <========= */
Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');

/* =========> End Frontend All Routes <========== */

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
