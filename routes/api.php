<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\NewsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/campaigns', [CampaignController::class, 'getCampaignsApi']);
Route::get('/news', [NewsController::class, 'getNewsApi']);
Route::get('/comments', [CommentController::class, 'index'])->name('api.comments');
Route::middleware('web')->group(function () {
    Route::get('/oauth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('oauth.redirect');
    Route::get('/oauth/{provider}/callback', [SocialiteController::class, 'callback'])->name('oauth.callback');
});
