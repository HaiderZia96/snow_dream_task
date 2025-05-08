<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Middleware\JWTMiddleware;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware([JWTMiddleware::class])->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    // Item routes
    Route::get('items', [ItemController::class, 'index']);
    Route::get('items/{item}', [ItemController::class, 'show']);
    Route::post('items', [ItemController::class, 'store']);
    Route::put('items/{item}', [ItemController::class, 'update']);
    Route::delete('items/{item}', [ItemController::class, 'destroy']);
});
