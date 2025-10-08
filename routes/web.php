<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Dashboard\MasterData\KaryawanController;
use App\Http\Controllers\Dashboard\MasterData\PelangganController;
use App\Http\Controllers\Dashboard\MasterData\ProduksiController;
use App\Http\Controllers\Dashboard\MasterData\PupukController;
use App\Http\Controllers\Dashboard\MasterData\RoleController;
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

        // Master Data
        Route::controller(RoleController::class)->group(function () {
            Route::get('/role', 'index')->name('dashboard.master-data.role');
        });

        Route::controller(KaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('dashboard.master-data.karyawan');
        });

        Route::controller(PelangganController::class)->group(function () {
            Route::get('/pelanggan', 'index')->name('dashboard.master-data.pelanggan');
        });

        Route::controller(PupukController::class)->group(function () {
            Route::get('/pupuk', 'index')->name('dashboard.master-data.pupuk');
        });

        Route::controller(ProduksiController::class)->group(function () {
            Route::get('/produksi', 'index')->name('dashboard.master-data.produksi');
        });
    });
});
