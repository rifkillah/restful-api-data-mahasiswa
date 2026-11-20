<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/client-mahasiswa');
});

Route::get('/client-mahasiswa', function () {
    return view('client_mahasiswa');
});
