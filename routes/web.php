<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Dashboard\MasterData\KaryawanController;
use App\Http\Controllers\Dashboard\MasterData\PelangganController;
use App\Http\Controllers\Dashboard\MasterData\ProduksiController;
use App\Http\Controllers\Dashboard\MasterData\PupukController;
use App\Http\Controllers\Dashboard\MasterData\RoleController;
use App\Http\Controllers\Dashboard\Transaksi\DetailPesananController;
use App\Http\Controllers\Dashboard\Transaksi\PesananController;
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
            Route::post('/role', 'store')->name('dashboard.master-data.role.store');
            Route::get('/role/{id}/edit', 'edit')->name('dashboard.master-data.role.edit');
            Route::put('/role/{id}', 'update')->name('dashboard.master-data.role.update');
            Route::delete('/role/{id}', 'destroy')->name('dashboard.master-data.role.destroy');
        });

        Route::controller(KaryawanController::class)->group(function () {
            Route::get('/karyawan', 'index')->name('dashboard.master-data.karyawan');
            Route::post('/karyawan', 'store')->name('dashboard.master-data.karyawan.store');
            Route::get('/karyawan/{id}/edit', 'edit')->name('dashboard.master-data.karyawan.edit');
            Route::put('/karyawan/{id}', 'update')->name('dashboard.master-data.karyawan.update');
            Route::delete('/karyawan/{id}', 'destroy')->name('dashboard.master-data.karyawan.destroy');
        });

        Route::controller(PelangganController::class)->group(function () {
            Route::get('/pelanggan', 'index')->name('dashboard.master-data.pelanggan');
            Route::post('/pelanggan', 'store')->name('dashboard.master-data.pelanggan.store');
            Route::get('/pelanggan/{id}/edit', 'edit')->name('dashboard.master-data.pelanggan.edit');
            Route::put('/pelanggan/{id}', 'update')->name('dashboard.master-data.pelanggan.update');
            Route::delete('/pelanggan/{id}', 'destroy')->name('dashboard.master-data.pelanggan.destroy');
        });

        Route::controller(PupukController::class)->group(function () {
            Route::get('/pupuk', 'index')->name('dashboard.master-data.pupuk');
            Route::post('/pupuk', 'store')->name('dashboard.master-data.pupuk.store');
            Route::get('/pupuk/{id}/edit', 'edit')->name('dashboard.master-data.pupuk.edit');
            Route::put('/pupuk/{id}', 'update')->name('dashboard.master-data.pupuk.update');
            Route::delete('/pupuk/{id}', 'destroy')->name('dashboard.master-data.pupuk.destroy');
        });

        Route::controller(ProduksiController::class)->group(function () {
            Route::get('/produksi', 'index')->name('dashboard.master-data.produksi');
            Route::post('/produksi', 'store')->name('dashboard.master-data.produksi.store');
            Route::get('/produksi/{id}/edit', 'edit')->name('dashboard.master-data.produksi.edit');
            Route::put('/produksi/{id}', 'update')->name('dashboard.master-data.produksi.update');
            Route::delete('/produksi/{id}', 'destroy')->name('dashboard.master-data.produksi.destroy');
        });

        // Transaksi
        Route::controller(PesananController::class)->group(function () {
            Route::get('/pesanan', 'index')->name('dashboard.transaksi.pesanan');
            Route::post('/pesanan', 'store')->name('dashboard.transaksi.pesanan.store');
            Route::get('/pesanan/{id}/edit', 'edit')->name('dashboard.transaksi.pesanan.edit');
            Route::put('/pesanan/{id}', 'update')->name('dashboard.transaksi.pesanan.update');
            Route::delete('/pesanan/{id}', 'destroy')->name('dashboard.transaksi.pesanan.destroy');
        });

        Route::controller(DetailPesananController::class)->group(function () {
            Route::get('/detail-pesanan/{id}', 'index')->name('dashboard.transaksi.detail-pesanan');
            Route::put('/detail-pesanan/{id}/update-pembayaran', 'updatePembayaran')->name('dashboard.transaksi.detail-pesanan.update-pembayaran');
            Route::put('/detail-pesanan/{id}/update-pengiriman', 'updatePengiriman')->name('dashboard.transaksi.detail-pesanan.update-pengiriman');
        });
    });
});
