<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Route untuk tes
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, API!']);
});

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);



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
      
    // Admin bisa semua melihat riwayat transaksi
    Route::get('/transactions', [TransactionController::class, 'index']);   
    // Admin bisa melihat riwayat transaksi dengan id
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/daily', [ReportController::class, 'salesDaily']);
    Route::get('/monthly', [ReportController::class, 'salesMonthly']);
});

// Grup route untuk Kasir
Route::middleware(['auth:sanctum', 'role:kasir'])->prefix('kasir')->group(function () {
    // Kasir hanya bisa melihat daftar produk
    Route::get('/products', [ProductController::class, 'index']);
    
    // Kasir hanya bisa melihat detail produk berdasarkan ID
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // Kasir bisa melakukan transaksi
    Route::apiResource('/transactions', TransactionController::class);

    Route::get('/daily', [ReportController::class, 'salesDaily']);
    Route::get('/monthly', [ReportController::class, 'salesMonthly']);
});

//Tetsting
Route::get('/ping', function () {
    return response()->json(['message' => 'API Connected!']);
});

