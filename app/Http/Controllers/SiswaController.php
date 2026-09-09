<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    // Tampilkan data siswa ke halaman
    public function index()
    {
        $siswas = Siswa::latest()->get();
        return view('siswa', compact('siswas'));
    }

    // Proses Tambah Siswa Baru
    public function store(Request $request)
    {
        // Kalau NIS kosong, kita buatin otomatis (Misal: 2026001)
        $nis = $request->nis ?? date('Y') . str_pad(Siswa::count() + 1, 3, '0', STR_PAD_LEFT);

        Siswa::create([
            'nis' => $nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'status' => 'Bebas Tanggungan'
        ]);

        return response()->json(['status' => 'success']);
    }

    // Proses Update Data Siswa
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update([
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas
        ]);

        return response()->json(['status' => 'success']);
    }

    // Proses Hapus Siswa
    public function destroy($id)
    {
        Siswa::destroy($id);
        return response()->json(['status' => 'success']);
    }
}