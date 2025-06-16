<?php

use App\Http\Controllers\AvailableDonationController;
use App\Http\Controllers\AvailableNewsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController; // Pastikan ini diimpor
use App\Http\Controllers\NewsDetailController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\CampaignDetailController;
use App\Http\Controllers\DonationController;


Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/available-donation', [AvailableDonationController::class, 'index'])->name('available-donation');
    Route::get('/news', [AvailableNewsController::class, 'index'])->name('news');
    Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
    Route::get('/campaigns/{slug}', [CampaignDetailController::class, 'index'])->name('campaigns-detail');
    Route::get('/news/{slug}', [NewsDetailController::class, 'index'])->name('news-detail');
    Route::get('/category/{slug}', function ($slug) {
        return Inertia::render('Categories/Show', ['categorySlug' => $slug]);
    })->name('show-category');
    Route::get('/donation/{slug}', [DonationController::class, 'showDonationForm'])->name('donation.form');
    Route::post('/donation/{slug}', [DonationController::class, 'processDonation'])->name('donation.process');
    Route::get('/donation/success/{donation}', [DonationController::class, 'donationSuccess'])->name('donation.success');

});
