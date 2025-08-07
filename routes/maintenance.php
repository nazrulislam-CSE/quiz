<?php

use App\Http\Controllers\Admin\MaintenanceController;
use Illuminate\Support\Facades\Route;

// Route::group(['prefix' => 'manager-panel-' . date('d') . '', 'as' => 'maintenance.'], function () {
//     Route::get('', [MaintenanceController::class, 'index'])->name('auth');
//     Route::post('', [MaintenanceController::class, 'index'])->name('index');
//     Route::post('cacheClear', [MaintenanceController::class, 'cacheClear'])->name('cacheClear');
//     Route::post('optimize', [MaintenanceController::class, 'optimize'])->name('optimize');
//     Route::post('viewClear', [MaintenanceController::class, 'viewClear'])->name('viewClear');
//     Route::post('routeClear', [MaintenanceController::class, 'routeClear'])->name('routeClear');
//     Route::post('configClear', [MaintenanceController::class, 'configClear'])->name('configClear');
//     Route::post('optimizeClear', [MaintenanceController::class, 'optimizeClear'])->name('optimizeClear');
//     Route::post('clearResetTokens', [MaintenanceController::class, 'clearResetTokens'])->name('clearResetTokens');
//     Route::post('databaseBackup', [MaintenanceController::class, 'databaseBackup'])->name('databaseBackup');
//     Route::post('clearCompiled', [MaintenanceController::class, 'clearCompiled'])->name('clearCompiled');
//     Route::post('cronJob', [MaintenanceController::class, 'cronJob'])->name('cronJob');
//     Route::post('storage-link', [MaintenanceController::class, 'storage_link'])->name('storage_link');
//     Route::post('migrate', [MaintenanceController::class, 'migrate'])->name('migrate');
//     Route::post('migrate_fresh', [MaintenanceController::class, 'migrate_fresh'])->name('migrate_fresh');
//     Route::post('seed', [MaintenanceController::class, 'seed'])->name('seed');

//     Route::post('debugbarClear', [MaintenanceController::class, 'debugbarClear'])->name('debugbarClear');
//     Route::post('eventClear', [MaintenanceController::class, 'eventClear'])->name('eventClear');
// });
