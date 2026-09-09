<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Siswa;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman dashboard & mengambil stok barang
    public function index()
    {
        // Ambil semua barang yang stoknya lebih dari 0 buat dimunculin di dropdown form
        $barangs = Barang::where('stok', '>', 0)->get(); 
        
        return view('dashboard', compact('barangs'));
    }

    // Fungsi untuk memproses data dari form peminjaman cepat
    public function storePinjam(Request $request)
    {
        // 1. Simpan data anak yang minjam ke Data Siswa
        Siswa::create([
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
        ]);

        // 2. Simpan jejaknya ke Data Peminjaman
        Peminjaman::create([
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'barang_id' => $request->barang_id,
            'status' => 'Dipinjam'
        ]);

        // 3. Kurangi otomatis stok barang yang dipinjam
        $barang = Barang::find($request->barang_id);
        if($barang) {
            $barang->decrement('stok'); // Kurangi 1
            if($barang->stok == 0) {
                $barang->update(['status' => 'Habis']);
            }
        }

        // Kembalikan respon sukses ke animasi SweetAlert
        return response()->json(['status' => 'success']);
    }
}
