<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoworkController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CoworkController::class, 'home'])->name('home');

// Cowork Spaces
Route::get('/spaces', [CoworkController::class, 'index'])->name('spaces.index');
Route::get('/spaces/live-search', [CoworkController::class, 'liveSearch'])->name('spaces.live-search');
Route::get('/spaces/{spaceSlug}/{subSpaceSlug}', [CoworkController::class, 'showSubSpace'])->name('spaces.subspaces.show');
Route::get('/spaces/{slug}', [CoworkController::class, 'show'])->name('spaces.show');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::post('/spaces/{slug}/comments', [CoworkController::class, 'storeSpaceComment'])->name('spaces.comments.store');
    Route::post('/spaces/{spaceSlug}/{subSpaceSlug}/comments', [CoworkController::class, 'storeSubSpaceComment'])->name('spaces.subspaces.comments.store');
    Route::post('/spaces/{slug}/likes/toggle', [CoworkController::class, 'toggleSpaceLike'])->name('spaces.likes.toggle');
    Route::post('/spaces/{spaceSlug}/{subSpaceSlug}/likes/toggle', [CoworkController::class, 'toggleSubSpaceLike'])->name('spaces.subspaces.likes.toggle');
});

// Auth UI
Route::get('/login-register', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login-register/otp/send', [AuthController::class, 'sendOtp'])->name('auth.otp.send');
Route::post('/login-register/otp/verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

// Support
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
