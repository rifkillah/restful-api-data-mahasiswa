<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController; // <-- Pastikan baris use ini ada

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Otomatis membuat endpoint CRUD di URL: http://127.0.0.1:8000/api/mahasiswa
Route::apiResource('mahasiswa', MahasiswaApiController::class);
