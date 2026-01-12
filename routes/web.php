<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ViolationController;

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('customer-update-pass', [HomeController::class, 'customer_update_pass'])->name('home.customer-update-pass');

    Route::prefix('violation')->group(function () {
        Route::get('/', [ViolationController::class, 'index'])->name('violation');
        Route::get('data', [ViolationController::class, 'data'])->name('violation.data');
        Route::post('/', [ViolationController::class, 'store']);
        Route::post('update', [ViolationController::class, 'update'])->name('violation.update');
        Route::delete('delete/{id}', [ViolationController::class, 'destroy'])->name('violation.delete');
    });
});