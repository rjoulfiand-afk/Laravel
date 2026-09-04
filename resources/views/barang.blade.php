@extends('layouts.app')

@section('title', 'Laravel')

@section('content')
<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Data Barang</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola daftar inventaris dan ketersediaan aset sekolah.</p>
        </div>
        <button class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm flex items-center gap-2 transition-all">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Barang
        </button>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 border-collapse">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/60">
                    <tr>
                        <th class="px-8 py-4">Kode Barang</th>
                        <th class="px-6 py-4">Nama Aset</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">INV-2026-001</td>
                        <td class="px-6 py-5 font-bold text-slate-900">Proyektor Epson EB-X05</td>
                        <td class="px-6 py-5">Elektronik</td>
                        <td class="px-6 py-5 text-center font-bold text-slate-700">4</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-bold border border-emerald-200/60">Tersedia</span>
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    <!-- Baris 2 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">INV-2026-002</td>
                        <td class="px-6 py-5 font-bold text-slate-900">Kamera DSLR Canon 1300D</td>
                        <td class="px-6 py-5">Multimedia</td>
                        <td class="px-6 py-5 text-center font-bold text-slate-700">0</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-red-50 text-red-600 px-3 py-1.5 rounded-md text-xs font-bold border border-red-200/60">Habis</span>
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection