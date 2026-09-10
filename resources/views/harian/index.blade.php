@extends('harian.layout')

@section('content')
    <!-- Header Rapi -->
    <div class="flex justify-between items-center mb-8 mt-2">
        <div>
            <h2 class="text-gray-500 text-xs font-semibold tracking-wider uppercase mb-1">Overview Harian</h2>
            <h1 class="text-2xl font-extrabold text-gray-900">
                Halo, Rixsan! <span class="text-red-600">✌️</span>
            </h1>
        </div>
        <!-- Foto Profil / Inisial -->
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 font-bold border border-red-200 shadow-sm">
            R
        </div>
    </div>

    <!-- Card Saldo (Merah Menyala Premium) -->
    <div class="bg-red-600 rounded-3xl p-6 shadow-[0_15px_30px_rgba(220,38,38,0.25)] relative overflow-hidden mb-8">
        <!-- Dekorasi Ornamen Lingkaran -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-8 -mb-8"></div>
        
        <p class="text-red-100 text-xs font-medium mb-1 flex items-center gap-2">
            <i class="fas fa-shield-alt"></i> Saldo Tabungan
        </p>
        <h2 class="text-4xl font-extrabold text-white tracking-tight mb-6">Rp 0</h2>
        
        <div class="flex gap-3 relative z-10">
            <!-- Tombol Masuk (Putih) -->
            <button class="flex-1 bg-white text-red-600 hover:bg-gray-50 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex justify-center items-center gap-2">
                <i class="fas fa-arrow-down"></i> Masuk
            </button>
            <!-- Tombol Keluar (Merah Gelap) -->
            <button class="flex-1 bg-red-700 text-white hover:bg-red-800 border border-red-500 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex justify-center items-center gap-2">
                <i class="fas fa-arrow-up"></i> Keluar
            </button>
        </div>
    </div>

    <!-- Section Aktivitas -->
    <div class="mb-4 flex justify-between items-end">
        <h3 class="text-lg font-bold text-gray-800">Aktivitas Hari Ini</h3>
        <a href="#" class="text-xs font-bold text-red-600 hover:underline">Lihat Semua</a>
    </div>
    
    <!-- Tampilan Kosong (Empty State) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-inbox text-2xl"></i>
        </div>
        <h4 class="text-gray-800 font-bold mb-1">Belum ada aktivitas</h4>
        <p class="text-xs text-gray-400">Mulai catat tabungan, tugas, atau pengeluaranmu hari ini.</p>
    </div>
@endsection