<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CoworkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\AuthController;

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

// Support
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
