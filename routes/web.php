<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use Illuminate\Support\Facades\Route;

// Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'registerView')->name('register');
    Route::post('/register', 'registerStore')->name('register.store');
    Route::get('/login', 'loginView')->name('login');
    Route::post('/login', 'loginStore')->name('login.store');
    Route::post('/logout', 'logout')->name('logout');
});

// Frontend
Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.home');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardHomeController::class, 'index'])->name('dashboard.home');
    });
});
