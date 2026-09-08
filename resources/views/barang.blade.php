@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<!-- CDN SweetAlert2 & Animate.css untuk efek elegan -->
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
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">INV-2026-001</td>
                        <td class="px-6 py-5 font-bold text-slate-900">Proyektor Epson EB-X05</td>
                        <td class="px-6 py-5">Elektronik</td>
                        <td class="px-6 py-5 text-center font-bold text-slate-700">4</td>
                        <td class="px-6 py-5 text-center">
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-bold border border-emerald-200/60">Tersedia</span>
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button onclick="editBarang('INV-2026-001', 'Proyektor Epson EB-X05', 4)" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button onclick="hapusBarang('INV-2026-001', 'Proyektor Epson EB-X05')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Konfigurasi standar efek premium untuk semua popup
    const customSwal = Swal.mixin({
        customClass: {
            popup: 'rounded-2xl shadow-2xl border border-slate-100',
            confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
            cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all mx-2'
        },
        buttonsStyling: false,
        showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
        hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' },
        backdrop: `rgba(15, 23, 42, 0.5)` // Efek background gelap elegan
    });

    function tambahBarang() {
        customSwal.fire({
            title: 'Tambah Aset Baru',
            html: `
                <div class="space-y-4 mt-4">
                    <input id="swal-input1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Nama Barang">
                    <input id="swal-input2" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Jumlah Stok">
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Simpan Data',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                customSwal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Barang baru telah ditambahkan ke inventaris.'
                })
            }
        });
    }

    function editBarang(kode, nama, stok) {
        customSwal.fire({
            title: 'Edit Data Barang',
            html: `
                <p class="text-xs text-slate-400 font-mono mb-4">ID: ${kode}</p>
                <div class="space-y-4">
                    <input id="swal-input1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${nama}">
                    <input id="swal-input2" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${stok}">
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                customSwal.fire({
                    icon: 'success',
                    title: 'Diperbarui!',
                    text: 'Data barang berhasil diubah.'
                })
            }
        });
    }

    function hapusBarang(kode, nama) {
        customSwal.fire({
            title: 'Yakin mau dihapus?',
            html: `Data <b>${nama}</b> akan hilang permanen dari sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
                cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all mx-2',
                popup: 'rounded-2xl shadow-2xl border border-slate-100'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                customSwal.fire({
                    title: 'Terhapus!',
                    text: 'Barang sudah dikeluarkan dari inventaris.',
                    icon: 'success'
                })
            }
        });
    }
</script>
@endsection