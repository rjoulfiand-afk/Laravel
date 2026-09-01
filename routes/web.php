<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); });

Route::post('/proses-login', function (Request $request) {
    if ($request->username == 'rixsan' && $request->password == 'admin123') {
        session(['is_logged_in' => true, 'nama_user' => 'Rixsan Joulfiand', 'role_user' => 'Super Admin']);
        return redirect('/dashboard');
    }
    return back()->with('error', 'Username atau Password salah bro!');
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});

// --- Rute Halaman Dalam (Membutuhkan Login) ---
Route::get('/dashboard', function () {
    if (!session('is_logged_in')) return redirect('/');
    return view('dashboard');
});

Route::get('/barang', function () {
    if (!session('is_logged_in')) return redirect('/');
    return view('barang');
});

Route::get('/transaksi', function () {
    if (!session('is_logged_in')) return redirect('/');
    return view('transaksi');
});

Route::get('/siswa', function () {
    if (!session('is_logged_in')) return redirect('/');
    return view('siswa');
});