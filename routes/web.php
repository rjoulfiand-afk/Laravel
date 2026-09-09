<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SiswaController;

// ==========================================
// RUTE AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/', function () { 
    return view('login'); 
});

Route::post('/proses-login', function (Request $request) {
    if ($request->username == 'rixsan' && $request->password == 'admin123') {
        session([
            'is_logged_in' => true, 
            'nama_user' => 'Rixsan Joulfiand', 
            'role_user' => 'Super Admin'
        ]);
        return redirect('/dashboard');
    }
    return back()->with('error', 'Username atau Password salah bro!');
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});

// ==========================================
// RUTE DALAM (WAJIB LOGIN)
// ==========================================
// Kita bungkus semua rute di bawah ini pakai Middleware Closure.
// Jadi kalau ada yang belum login, otomatis ditendang balik ke '/' (halaman login).
Route::middleware([\App\Http\Middleware\CekLogin::class])->group(function () {
    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/dashboard/pinjam', [DashboardController::class, 'storePinjam']);

    // --- DATA BARANG ---
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    // --- DATA SISWA ---
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::put('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);

    // --- TRANSAKSI (Mockup sementara sampai lo bikin Controller-nya) ---
    Route::get('/transaksi', function () {
        return view('transaksi');
    });

});
// Pintu masuk khusus buat App Harian Boss Jul
Route::get('/harian', function () {
    return view('harian.index');
});