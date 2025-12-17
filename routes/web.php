<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CSRF Refresh Route
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard - Semua role bisa akses
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management - Hanya Super Admin
    Route::middleware('role:super_admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // POS / Kasir - Hanya Super Admin dan Admin
    Route::get('/pos', [PosController::class, 'index'])->name('pos')->middleware('role:admin');

    // Master Data - Barang
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        // Create & Edit hanya untuk Super Admin dan Admin
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [BarangController::class, 'create'])->name('create');
            Route::get('/{barang}/edit', [BarangController::class, 'edit'])->name('edit');
        });
    });

    // Master Data - Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [PelangganController::class, 'create'])->name('create');
            Route::get('/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('edit');
        });
    });

    // Master Data - Pemasok
    Route::prefix('pemasok')->name('pemasok.')->group(function () {
        Route::get('/', [PemasokController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [PemasokController::class, 'create'])->name('create');
            Route::get('/{pemasok}/edit', [PemasokController::class, 'edit'])->name('edit');
        });
    });

    // Master Data - Gudang
    Route::prefix('gudang')->name('gudang.')->group(function () {
        Route::get('/', [GudangController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [GudangController::class, 'create'])->name('create');
            Route::get('/{gudang}/edit', [GudangController::class, 'edit'])->name('edit');
        });
    });

    // Transaksi - Penjualan
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/{penjualan}', [PenjualanController::class, 'show'])->name('show');
    });

    // Transaksi - Pembelian
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [PembelianController::class, 'create'])->name('create');
        });
        Route::get('/{pembelian}', [PembelianController::class, 'show'])->name('show');
    });

    // Transaksi - Pengeluaran
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
            Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        });
    });

    // Laporan - Semua role bisa akses
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laba-rugi');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
    });
});
