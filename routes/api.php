<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, API!']);
});

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

Route::middleware(['auth:sanctum', 'role:kasir'])->get('/kasir/dashboard', function () {
    return response()->json(['message' => 'Welcome Kasir']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->apiResource('/users', UserController::class);