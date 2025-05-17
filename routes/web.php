<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanKeuanganController; // Pastikan Anda memiliki controller ini

Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');

Route::get('/', function () {
    return view('welcome');
    
});
