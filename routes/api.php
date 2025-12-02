<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JadwalController as ApiJadwalController;
use App\Http\Controllers\Api\NilaiController as ApiNilaiController;
use App\Http\Controllers\Api\MahasiswaController as ApiMahasiswaController;

Route::middleware('auth:sanctum')->prefix('api')->name('api.')->group(function () {
    // Jadwal API
    Route::get('/jadwal/{periodeId}', [ApiJadwalController::class, 'getByPeriode'])->name('jadwal.periode');
    Route::get('/jadwal/mahasiswa/{mahasiswaId}/{periodeId}', [ApiJadwalController::class, 'getByMahasiswaPeriode'])->name('jadwal.mahasiswa.periode');
    
    // Nilai API
    Route::get('/nilai/mahasiswa/{mahasiswaId}/{periodeId}', [ApiNilaiController::class, 'getByMahasiswaPeriode'])->name('nilai.mahasiswa.periode');
    
    // Mahasiswa API
    Route::get('/mahasiswa/{id}', [ApiMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/mahasiswa/{id}/krs', [ApiMahasiswaController::class, 'getKrs'])->name('mahasiswa.krs');
    Route::get('/mahasiswa/{id}/transkrip', [ApiMahasiswaController::class, 'getTranskrip'])->name('mahasiswa.transkrip');
});