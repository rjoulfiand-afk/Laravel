<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Tampilkan Halaman Login
Route::get('/', function () {
    return view('login');
});

// 2. Proses Pengecekan Login
Route::post('/proses-login', function (Request $request) {
    // Mengecek kecocokan username dan password
    if ($request->username == 'rixsan' && $request->password == 'admin123') {
        // Simpan data ke session sementara
        session([
            'is_logged_in' => true, 
            'nama_user' => 'Rixsan Joulfiand',
            'role_user' => 'Super Admin'
        ]);
        return redirect('/dashboard');
    }
    // Jika salah, kembalikan ke halaman login dengan pesan error
    return back()->with('error', 'Username atau Password salah bro!');
});

// 3. Tampilkan Dashboard (Hanya jika sudah login)
Route::get('/dashboard', function () {
    if (!session('is_logged_in')) {
        return redirect('/');
    }
    return view('dashboard');
});

// 4. Proses Keluar (Logout)
Route::get('/logout', function () {
    session()->flush(); // Hapus semua session
    return redirect('/');
});