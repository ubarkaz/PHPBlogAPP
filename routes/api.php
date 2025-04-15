<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Htttp\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogOrionController;
use Orion\Facades\Orion;


Route::apiResource('users', UserController::class);
Route::apiResource('blogs', BlogController::class);
Route::apiResource('comments', CommentController::class);

//ThrottleRequests middleware to limit user creation to 1 every minute as a test case
//Route::middleware(['throttle:1,1'])->post('/users', [UserController::class, 'store']);

Route::middleware(['throttle:user-create'])->post('/users', [UserController::class, 'store']);


//Restore routes for soft deleted records
Route::patch('users/{id}/restore', [UserController::class, 'restore']);
Route::patch('blogs/{id}/restore', [BlogController::class, 'restore']);
Route::patch('comments/{id}/restore', [CommentController::class, 'restore']);


//Permanent delete routes for users
Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete']);
Route::delete('blogs/{id}/force-delete', [BlogController::class, 'forceDelete']);
Route::delete('comments/{id}/force-delete', [CommentController::class, 'forceDelete']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [RegisteredUserController::class, 'logout']);

//Orion routes
Orion::resource('blogs-orion', BlogOrionController::class);
