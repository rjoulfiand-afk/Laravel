@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<!-- Tambahkan CDN SweetAlert2 & Animate.css -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Overview Interaktif</h1>
            <p class="text-sm text-slate-500 font-medium">Pantau lalu lintas peminjaman aset SMKN 10 Surabaya secara real-time.</p>
        </div>
    </div>

    <!-- Banner Welcome -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl p-8 shadow-lg shadow-indigo-200 flex items-center justify-between mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Selamat datang kembali, Rixsan! 👋</h2>
            <p class="text-indigo-100">Sistem inventaris berjalan normal. Pantau stok barang sebelum menyetujui peminjaman baru.</p>
        </div>
        <i data-feather="cpu" class="w-32 h-32 absolute -right-4 -bottom-4 text-white opacity-20"></i>
    </div>

    <!-- Tiga Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-feather="box" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Aset</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">142</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <i data-feather="refresh-cw" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">12</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <i data-feather="users" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">850</h3>
            </div>
        </div>
    </div>

    <!-- Form Peminjaman Cepat -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i data-feather="edit" class="w-5 h-5 text-indigo-500"></i> Form Peminjaman Cepat
            </h3>
        </div>
        <div class="p-8">
            <form id="formPeminjaman" onsubmit="prosesPinjam(event)" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Siswa</label>
                    <input type="text" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kelas / Jurusan</label>
                    <input type="text" required placeholder="Contoh: XII - RPL" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Barang</label>
                    <select required class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-slate-600">
                        <option value="" disabled selected>-- Pilih aset yang tersedia --</option>
                        <option value="1">Proyektor Epson EB-X05 (Stok: 4)</option>
                        <option value="2">Kabel HDMI 15 Meter (Stok: 12)</option>
                        <option value="3">Microphone Wireless (Stok: 2)</option>
                    </select>
                </div>
                <div class="md:col-span-2 mt-2">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold tracking-wide py-3.5 shadow-md hover:shadow-lg transition-all duration-300">
                        PROSES PEMINJAMAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function prosesPinjam(e) {
        e.preventDefault(); // Mencegah reload halaman
        
        Swal.fire({
            title: 'Memproses Data...',
            text: 'Mendaftarkan siswa dan mengamankan stok barang.',
            icon: 'info',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        }).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Peminjaman Berhasil!',
                text: 'Data telah disinkronisasi ke menu Data Siswa dan Data Barang.',
                confirmButtonColor: '#4f46e5',
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
            });
            document.getElementById('formPeminjaman').reset();
        });
    }
</script>
@endsection