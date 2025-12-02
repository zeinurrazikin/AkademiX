<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Dosen\NilaiController;
use App\Http\Controllers\Dosen\KrsController;

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal routes
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/{jadwalId}/kelas', [JadwalController::class, 'showJadwalKelas'])->name('jadwal.kelas');
    
    // Nilai routes
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/{jadwalId}/input', [NilaiController::class, 'showInputNilai'])->name('nilai.input');
    Route::post('/nilai/{jadwalId}', [NilaiController::class, 'storeNilai'])->name('nilai.store');
    
    // KRS routes
    Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
    Route::get('/krs/{krsId}', [KrsController::class, 'show'])->name('krs.show');
    Route::post('/krs/{krsId}/approve', [KrsController::class, 'approve'])->name('krs.approve');
    Route::post('/krs/{krsId}/reject', [KrsController::class, 'reject'])->name('krs.reject');
});