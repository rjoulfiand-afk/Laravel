@extends('harian.layout')

@section('content')
    <!-- Header Greeting -->
    <div class="mb-8 mt-2">
        <h2 class="text-gray-400 text-sm font-semibold tracking-wider">SELAMAT DATANG,</h2>
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-indigo-300">
            Boss Jul!
        </h1>
    </div>

    <!-- Card Saldo Estetik -->
    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl shadow-2xl relative overflow-hidden">
        <!-- Efek Cahaya di pojok card -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-violet-500 rounded-full blur-3xl opacity-30"></div>
        
        <p class="text-gray-300 text-sm mb-1"><i class="fas fa-wallet mr-2"></i>Total Tabungan</p>
        <h2 class="text-4xl font-bold text-white tracking-tight mb-4">Rp 0</h2>
        
        <div class="flex gap-3 mt-4">
            <button class="flex-1 bg-violet-600 hover:bg-violet-700 text-white py-3 rounded-2xl text-sm font-bold transition">
                <i class="fas fa-arrow-down mr-2"></i> Masuk
            </button>
            <button class="flex-1 bg-white/10 hover:bg-white/20 border border-white/20 text-white py-3 rounded-2xl text-sm font-bold transition">
                <i class="fas fa-arrow-up mr-2"></i> Keluar
            </button>
        </div>
    </div>
@endsection