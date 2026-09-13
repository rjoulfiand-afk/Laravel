<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// RUTE UTAMA: HARIAN | RIXSAN
// ==========================================

// Langsung tembak ke halaman Harian saat domain utama diakses
Route::get('/', function () {
    return view('harian.index');
});

// (Opsional) Biar kalau lu iseng ngetik /harian tetep nyambung ke tempat yang sama
Route::get('/harian', function () {
    return redirect('/');
});