@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Data Barang</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola daftar inventaris dan ketersediaan aset sekolah.</p>
        </div>
        
        <button onclick="tambahBarang()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm shadow-indigo-600/20 flex items-center gap-2 transition-all">
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
                    <!-- Looping data asli dari database -->
                    @forelse($barangs as $barang)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">{{ $barang->kode_barang }}</td>
                        <td class="px-6 py-5 font-bold text-slate-900">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-5">{{ $barang->kategori }}</td>
                        <td class="px-6 py-5 text-center font-bold text-slate-700">{{ $barang->stok }}</td>
                        <td class="px-6 py-5 text-center">
                            @if($barang->stok > 0)
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-bold border border-emerald-200/60">Tersedia</span>
                            @else
                                <span class="bg-red-50 text-red-600 px-3 py-1.5 rounded-md text-xs font-bold border border-red-200/60">Habis</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button onclick="editBarang({{ $barang->id }}, '{{ $barang->kode_barang }}', '{{ $barang->nama_barang }}', {{ $barang->stok }})" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button onclick="hapusBarang({{ $barang->id }}, '{{ $barang->nama_barang }}')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada data barang di inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const customSwal = Swal.mixin({
        customClass: {
            popup: 'rounded-2xl shadow-2xl border border-slate-100',
            confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
            cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all mx-2'
        },
        buttonsStyling: false,
        showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
        hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' },
        backdrop: `rgba(15, 23, 42, 0.5)`
    });

    // FUNGSI TAMBAH BARANG KE DATABASE
    function tambahBarang() {
        customSwal.fire({
            title: 'Tambah Aset Baru',
            html: `
                <div class="space-y-4 mt-4">
                    <input id="nama_barang" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Nama Barang (Cth: Proyektor)">
                    <input id="kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Kategori (Cth: Elektronik)">
                    <input id="stok" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Jumlah Stok">
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Simpan Data',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return {
                    nama_barang: document.getElementById('nama_barang').value,
                    kategori: document.getElementById('kategori').value,
                    stok: document.getElementById('stok').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/barang', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(result.value)
                }).then(() => window.location.reload());
            }
        });
    }

    // FUNGSI EDIT BARANG
    function editBarang(id, kode, nama, stok) {
        customSwal.fire({
            title: 'Edit Data Barang',
            html: `
                <p class="text-xs text-slate-400 font-mono mb-4">ID: ${kode}</p>
                <div class="space-y-4">
                    <input id="edit_nama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${nama}">
                    <input id="edit_stok" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${stok}">
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update Data',
            preConfirm: () => {
                return {
                    nama_barang: document.getElementById('edit_nama').value,
                    stok: document.getElementById('edit_stok').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/barang/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(result.value)
                }).then(() => window.location.reload());
            }
        });
    }

    // FUNGSI HAPUS BARANG
    function hapusBarang(id, nama) {
        customSwal.fire({
            title: 'Yakin mau dihapus?',
            html: `Data <b>${nama}</b> akan hilang permanen dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
                cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
                popup: 'rounded-2xl shadow-2xl border border-slate-100'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/barang/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                }).then(() => window.location.reload());
            }
        });
    }
</script>
@endsection