<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'dosen') {
        return redirect()->route('dosen.dashboard');
    } elseif (auth()->user()->role === 'mahasiswa') {
        return redirect()->route('mahasiswa.dashboard');
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Admin routes
require __DIR__.'/admin.php';

// Dosen routes
require __DIR__.'/dosen.php';

// Mahasiswa routes
require __DIR__.'/mahasiswa.php';

// API routes
require __DIR__.'/api.php';