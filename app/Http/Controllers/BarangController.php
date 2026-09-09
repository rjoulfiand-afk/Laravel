<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    // Tampilkan halaman dan ambil data dari database
    public function index()
    {
        $barangs = Barang::latest()->get();
        return view('barang', compact('barangs'));
    }

    // Proses Tambah Barang
    public function store(Request $request)
    {
        // Bikin kode unik otomatis (Contoh: INV-2026-001)
        $kode = 'INV-' . date('Y') . '-' . str_pad(Barang::count() + 1, 3, '0', STR_PAD_LEFT);

        Barang::create([
            'kode_barang' => $kode,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori ?? 'Umum',
            'stok' => $request->stok,
            'status' => $request->stok > 0 ? 'Tersedia' : 'Habis'
        ]);

        return response()->json(['status' => 'success']);
    }

    // Proses Update Barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'status' => $request->stok > 0 ? 'Tersedia' : 'Habis'
        ]);

        return response()->json(['status' => 'success']);
    }

    // Proses Hapus Barang
    public function destroy($id)
    {
        Barang::destroy($id);
        return response()->json(['status' => 'success']);
    }
}