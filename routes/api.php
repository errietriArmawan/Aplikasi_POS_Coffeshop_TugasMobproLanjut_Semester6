<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

// Route untuk tes
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, API!']);
});

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);

// Route Dashboard untuk Admin
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

// Route Dashboard untuk Kasir
Route::middleware(['auth:sanctum', 'role:kasir'])->get('/kasir/dashboard', function () {
    return response()->json(['message' => 'Welcome Kasir']);
});

// Grup route untuk Admin
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    // Menambahkan prefix 'admin' pada users dan products
    Route::apiResource('/users', UserController::class); // Menambahkan prefix untuk users
    Route::resource('/products', ProductController::class); // Menambahkan prefix untuk products
});
