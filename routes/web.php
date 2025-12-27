<?php

use App\Livewire\BarangMasterTable;
use App\Livewire\StokBarangForm;
use App\Livewire\BarangMasterForm;

use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\BarangMasterController;


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


    // Superadmin Panel - Multi Gudang & Manajemen Akun
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/superadmin', function () {
            return view('superadmin');
        })->name('superadmin.panel');

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    // POS / Kasir - Hanya Super Admin dan Admin
    Route::get('/pos', [PosController::class, 'index'])->name('pos')->middleware('role:admin');

    // Master Data - Barang
    // Barang (Identitas)
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [App\Http\Controllers\BarangMasterController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [App\Http\Controllers\BarangMasterController::class, 'create'])->name('create');
            Route::get('/{barangMaster}/edit', [App\Http\Controllers\BarangMasterController::class, 'edit'])->name('edit');
        });
    });

    // Stok Barang (per Gudang)
    Route::prefix('stok-barang')->name('stok-barang.')->group(function () {
        Route::get('/', \App\Livewire\StokBarangTable::class)->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', StokBarangForm::class)->name('create');
            Route::get('/{barang}/edit', StokBarangForm::class)->name('edit');
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
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/create', [GudangController::class, 'create'])->name('create');
            Route::get('/{gudang}/edit', [GudangController::class, 'edit'])->name('edit');
        });
    });

    // Transaksi - Penjualan
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/termin/{penjualan}', \App\Livewire\PenjualanTerminUpdate::class)->name('termin.update');
        Route::get('/{penjualan}', [PenjualanController::class, 'show'])->name('show');
    });

    // Transaksi - Pembelian
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [PembelianController::class, 'create'])->name('create');
            Route::get('/kasir', \App\Livewire\PembelianKasirForm::class)->name('kasir');
        });
        // Kasir pembayaran termin global
        Route::get('/termin', function() {
            return view('pembelian.kasir-termin');
        })->name('termin');
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

    // Master Data - Barang Master
    Route::prefix('barang-master')->name('barang-master.')->group(function () {
        Route::get('/', [BarangMasterTable::class, 'index'])->name('index');
        Route::get('/create', BarangMasterForm::class)->name('create');
        Route::get('/{barangMaster}/edit', BarangMasterForm::class)->name('edit');
    });

    Route::resource('barang-master', BarangMasterController::class);
});

require __DIR__.'/stok.php';
