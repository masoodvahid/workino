<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoworkController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

// Cowork Spaces
Route::get('/coworks', [CoworkController::class, 'index'])->name('coworks.index');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// User Profile (Mocked as accessible for UI phase)
Route::get('/profile', [UserController::class, 'index'])->name('profile.index');

// Auth UI
Route::get('/login-register', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login-register/otp/send', [AuthController::class, 'sendOtp'])->name('auth.otp.send');
Route::post('/login-register/otp/verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

// Support
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
