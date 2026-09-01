@extends('layouts.app')

@section('title', 'Laravel')

@section('content')
<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Overview Interaktif</h1>
            <p class="text-sm text-slate-500 font-medium">Pantau lalu lintas peminjaman aset SMKN 10 Surabaya.</p>
        </div>
    </div>

    <!-- Stats Cards (Sama seperti sebelumnya, sisipkan kembali div grid card 1-4 di sini) -->
    <!-- Untuk menyingkat instruksi, saya asumsikan bagian card dan tabel dashboard lo sudah tersimpan. -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mb-10">
        <h2 class="text-xl font-bold text-slate-800">Selamat datang di Dashboard, {{ session('nama_user') }}!</h2>
        <p class="text-slate-500 mt-2">Ini adalah halaman utama. Silakan navigasi ke menu Data Barang di sebelah kiri.</p>
    </div>
</div>
@endsection