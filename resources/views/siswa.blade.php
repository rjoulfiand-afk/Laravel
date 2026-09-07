@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Data Siswa</h1>
            <p class="text-sm text-slate-500 font-medium">Manajemen data siswa SMKN 10 Surabaya yang terdaftar sebagai peminjam.</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm shadow-indigo-600/20 flex items-center gap-2 transition-all">
            <i data-feather="user-plus" class="w-4 h-4"></i> Tambah Siswa
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 border-collapse">
                <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/60">
                    <tr>
                        <th class="px-8 py-4">NIS</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Kelas / Jurusan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">2026028</td>
                        <td class="px-6 py-5 font-bold text-slate-900">Rixsan Joulfiand</td>
                        <td class="px-6 py-5">XII - Rekayasa Perangkat Lunak</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-bold border border-emerald-200/60">Bebas Tanggungan</span>
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