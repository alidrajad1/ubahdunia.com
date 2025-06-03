<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Mengubah rute root agar langsung mengarah ke 'Dashboard'
Route::get('/', function () {
    return Inertia::render('Dashboard', [ // Mengarahkan ke komponen 'Dashboard'
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Rute dashboard tetap ada, tetapi sekarang rute root juga menunjuk ke sini
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
