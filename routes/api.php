<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// All URLs start with /api/v1. Example: POST /api/v1/login

Route::prefix('v1')->group(function () {
    // ===== PUBLIC (no token) =====
    // Login limited 5x per minute to block password guessing.
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:30,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    // ===== WAJIB TOKEN (header: Authorization: Bearer TOKEN) =====
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Product CRUD for logged-in users. Add role:admin to restrict to admins.
        Route::apiResource('products', ProductController::class);
    });
});
