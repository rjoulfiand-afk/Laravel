<?php

use Illuminate\Support\Facades\Route;

// Mengubah halaman utama ('/') langsung mengarah ke halaman login
Route::get('/', function () {
    return view('login');
});