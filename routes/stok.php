<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/stok', App\Livewire\StokTable::class)->name('stok.index');
});
