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
        Route::get('name-search', [ViolationController::class, 'name_search'])->name('violation.name-search');
        Route::get('article-search', [ViolationController::class, 'article_search'])->name('violation.article-search');
        Route::get('download-evidence/{id}', [ViolationController::class, 'download_evidence'])->name('violation.download-evidence');
        Route::get('download', [ViolationController::class, 'download'])->name('violation.download');
        Route::post('/', [ViolationController::class, 'store']);
        Route::delete('delete/{id}', [ViolationController::class, 'destroy'])->name('violation.delete');
    });
});