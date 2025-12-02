<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\KhsController;
use App\Http\Controllers\Mahasiswa\TranskripController;
use App\Http\Controllers\Mahasiswa\JadwalController;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // KRS routes
    Route::resource('krs', KrsController::class)->except(['create', 'edit', 'update']);
    Route::get('/krs/create', [KrsController::class, 'create'])->name('krs.create');
    Route::post('/krs', [KrsController::class, 'store'])->name('krs.store');
    
    // KHS routes
    Route::get('/khs', [KhsController::class, 'index'])->name('khs.index');
    Route::get('/khs/{periodeId}', [KhsController::class, 'show'])->name('khs.show');
    
    // Transkrip routes
    Route::get('/transkrip', [TranskripController::class, 'index'])->name('transkrip.index');
    Route::get('/transkrip/generate-pdf', [TranskripController::class, 'generatePdf'])->name('transkrip.generate-pdf');
    
    // Jadwal routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/all', [JadwalController::class, 'all'])->name('jadwal.all');
});