<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\RuangController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa routes
    Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
    Route::get('/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    
    // Dosen routes
    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::get('/dosen/{dosen}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::put('/dosen/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');
    
    // Mata Kuliah routes
    Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
    Route::get('/mata-kuliah/{mataKuliah}/edit', [MataKuliahController::class, 'edit'])->name('mata-kuliah.edit');
    Route::put('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])->name('mata-kuliah.update');
    Route::delete('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])->name('mata-kuliah.destroy');
    
    // Periode Akademik routes
    Route::resource('periode', PeriodeAkademikController::class)->except(['show']);
    Route::get('/periode/{periode}/edit', [PeriodeAkademikController::class, 'edit'])->name('periode.edit');
    Route::put('/periode/{periode}', [PeriodeAkademikController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{periode}', [PeriodeAkademikController::class, 'destroy'])->name('periode.destroy');
    Route::post('/periode/{periode}/set-aktif', [PeriodeAkademikController::class, 'setAktif'])->name('periode.set-aktif');
    
    // Jadwal routes
    Route::resource('jadwal', JadwalController::class)->except(['show']);
    Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    
    // Kelas routes
    Route::resource('kelas', KelasController::class)->except(['show']);
    Route::get('/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    
    // Ruang routes
    Route::resource('ruang', RuangController::class)->except(['show']);
    Route::get('/ruang/{ruang}/edit', [RuangController::class, 'edit'])->name('ruang.edit');
    Route::put('/ruang/{ruang}', [RuangController::class, 'update'])->name('ruang.update');
    Route::delete('/ruang/{ruang}', [RuangController::class, 'destroy'])->name('ruang.destroy');
});