@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Overview Interaktif</h1>
            <p class="text-sm text-slate-500 font-medium">Pantau lalu lintas peminjaman aset SMKN 10 Surabaya secara real-time.</p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl p-8 shadow-lg shadow-indigo-200 flex items-center justify-between mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-2">Selamat datang kembali, Rixsan! 👋</h2>
            <p class="text-indigo-100">Sistem inventaris berjalan normal. Pantau stok barang sebelum menyetujui peminjaman baru.</p>
        </div>
        <i data-feather="cpu" class="w-32 h-32 absolute -right-4 -bottom-4 text-white opacity-20"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center gap-5 transition-transform hover:-translate-y-1">
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-feather="box" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Aset</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">142</h3>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center gap-5 transition-transform hover:-translate-y-1">
            <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <i data-feather="refresh-cw" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">12</h3>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] flex items-center gap-5 transition-transform hover:-translate-y-1">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <i data-feather="users" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">850</h3>
            </div>
        </div>
    </div>
</div>
@endsection