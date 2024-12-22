<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route to get the authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes for authentication (registration, login, verification, logout)
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify/{token}', [AuthController::class, 'verify']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Protecting blog routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']); // Get blogs for the authenticated user
    Route::post('/blogs', [BlogController::class, 'store']); // Create a new blog
    Route::put('/blogs/{blog}', [BlogController::class, 'update']); // Update an existing blog
    Route::get('/user/blogs', [BlogController::class, 'getUserBlogs']);
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']); // Delete a blog
});

// Route to view a specific blog (can be accessed publicly or by authenticated users)
Route::get('/blogs/{blog}', [BlogController::class, 'show']); // Show a specific blog (no auth needed if public)

