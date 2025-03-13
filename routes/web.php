<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if the user is allowed to access the dashboard
    if (!Gate::denies('access-dashboard')) {
        abort(403, 'You are not allowed to access this'); // Show 403 error if the user is not allowed
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//test route to check if my admin email is printed
Route::get('/debug-user', function () {
    return auth()->user()->email;
});


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

