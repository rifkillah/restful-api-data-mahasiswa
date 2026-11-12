<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client_mahasiswa');
});

// Tambahkan rute ini untuk mengakses halaman portal konsumen API mahasiswa
Route::get('/client-mahasiswa', function () {
    return view('client_mahasiswa');
});
