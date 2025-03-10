<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication Routes (Manual Login & Registration)
Route::get('/manual-register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/manual-register', [RegisterController::class, 'register']);

Route::get('/manual-login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/manual-login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile Routes (Only accessible to authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Keep Laravel Breeze Auth Routes
require __DIR__.'/auth.php';

