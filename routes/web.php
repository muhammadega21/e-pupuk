<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Dashboard\MasterData\KaryawanController;
use App\Http\Controllers\Dashboard\MasterData\PelangganController;
use App\Http\Controllers\Dashboard\MasterData\ProduksiController;
use App\Http\Controllers\Dashboard\MasterData\PupukController;
use App\Http\Controllers\Dashboard\MasterData\RoleController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\Transaksi\DetailPesananController;
use App\Http\Controllers\Dashboard\Transaksi\PesananController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Models\District;
use App\Models\Regency;
use Illuminate\Support\Facades\Request;
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
Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
Route::get('/featured', [FrontendHomeController::class, 'produkFeatured'])->name('featured');
Route::get('/about', [FrontendHomeController::class, 'about'])->name('about');

Route::get('/produk/{slug}', [FrontendHomeController::class, 'produkDetail'])->name('produk.detail');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart', [CartController::class, 'cartStore'])->name('cart.store');
Route::post('/cart/update-qty', [CartController::class, 'updateQty'])->name('cart.updateQty');
Route::delete('/cart/{pesanan_id}/{barang_id}', [CartController::class, 'cartDestroy'])->name('cart.destroy');
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkoutStore'])->name('checkout.store');
Route::get('/checkout/success', [CheckoutController::class, 'checkoutSuccess'])->name('frontend.checkout.success');

Route::get(
    '/ajax/kota/{prov}',
    fn($prov) =>
    Regency::where('province_id', $prov)->get()
);

Route::get(
    '/ajax/kecamatan/{kota}',
    fn($kota) =>
    District::where('regency_id', $kota)->get()
);

Route::post('/ajax/hitung-ongkir', [CheckoutController::class, 'ajaxHitungOngkir']);


// Dashboard
Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardHomeController::class, 'index'])->name('dashboard.home');
        Route::middleware('admin')->group(function () {
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
        });
        Route::middleware('karyawan')->group(function () {

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
                Route::get('/produksi/exportPreview', 'previewPdf')->name('dashboard.master-data.produksi.export.pdf');
            });
        });

        // Transaksi
        Route::controller(PesananController::class)->group(function () {
            Route::get('/pesanan', 'index')->name('dashboard.transaksi.pesanan');
            Route::post('/pesanan', 'store')->name('dashboard.transaksi.pesanan.store');
            Route::get('/pesanan/{id}/edit', 'edit')->name('dashboard.transaksi.pesanan.edit');
            Route::put('/pesanan/{id}', 'update')->name('dashboard.transaksi.pesanan.update');
            Route::delete('/pesanan/{id}', 'destroy')->name('dashboard.transaksi.pesanan.destroy');
            Route::post('/pesanan/{id}/confirm-delivery', 'confirmDelivery')->name('dashboard.transaksi.pesanan.confirm-delivery');
            Route::get('/dashboard/transaksi/pesanan/exportPreview', 'previewPdf')->name('dashboard.transaksi.pesanan.previewPdf');
            Route::post('/ajax/hitung-ongkir', 'ajaxHitungOngkir');
        });

        // Detail Pesanan
        Route::controller(DetailPesananController::class)->group(function () {
            Route::get('/detail-pesanan/{id}', 'index')->name('dashboard.transaksi.detail-pesanan');
            Route::put('/detail-pesanan/{id}/update-pembayaran', 'updatePembayaran')->name('dashboard.transaksi.detail-pesanan.update-pembayaran');
            Route::put('/detail-pesanan/{id}/update-pengiriman', 'updatePengiriman')->name('dashboard.transaksi.detail-pesanan.update-pengiriman');
        });

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('dashboard.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('dashboard.profile.password');
    });
});
