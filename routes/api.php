<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController;

// Otomatis membuat endpoint CRUD di URL: http://127.0.0.1:8000/api/mahasiswa
Route::apiResource('mahasiswa', MahasiswaApiController::class);
