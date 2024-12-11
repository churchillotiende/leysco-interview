<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

// Public API route (no authentication)
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Existing clients route (no authentication)
Route::get('/clients', [ClientController::class, 'index']);

// Dashboard route (requires Passport authentication)
Route::middleware(['auth:api'])->get('/dashboard', function () {
    return response()->json([
        'message' => 'Dashboard data here',
    ]);
})->name('dashboard');

// Profile routes (authenticated via Passport)
Route::middleware(['auth:api'])->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';