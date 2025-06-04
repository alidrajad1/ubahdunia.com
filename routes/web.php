<?php

use App\Http\Controllers\AvailableDonationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController; // Pastikan ini diimpor
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\CampaignDetailController;

// Rute Halaman Utama (Landing Page)
// Ini adalah halaman yang akan diakses oleh pengguna yang belum login.
Route::get('/', [DashboardController::class, 'index'])->name('home');
// Grup Rute yang Memerlukan Otentikasi
// Semua rute di dalam grup ini hanya bisa diakses oleh pengguna yang sudah login.
Route::middleware([
    'auth:sanctum', // Middleware untuk otentikasi Sanctum
    config('jetstream.auth_session'), // Middleware untuk sesi otentikasi Jetstream
    'verified', // Middleware untuk memastikan email pengguna sudah diverifikasi
])->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Rute BARU untuk Donasi Tersedia, Berita, dan Volunteer ---
    Route::get('/available-donation', [AvailableDonationController::class, 'index'])->name('available-donation');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
     Route::get('/campaigns/{slug}', [CampaignDetailController::class, 'index'])->name('campaigns-detail');
    // -------------------------------------------------------------

    // Anda bisa menambahkan rute lain yang memerlukan otentikasi di sini
    // Contoh: Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
});

// Baris-baris yang dikomentari di bawah ini adalah bagian dari kode Anda sebelumnya,
// yang sekarang sudah diganti atau diatur ulang di atas.
// Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
//      ->group(function () {
//          Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
//      });
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });
