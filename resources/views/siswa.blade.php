@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<!-- Wajib untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- CDN SweetAlert2 & Animate.css -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="p-10 max-w-7xl mx-auto w-full">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Data Siswa</h1>
            <p class="text-sm text-slate-500 font-medium">Manajemen data siswa SMKN 10 Surabaya yang terdaftar sebagai peminjam.</p>
        </div>
        <button onclick="tambahSiswa()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm shadow-indigo-600/20 flex items-center gap-2 transition-all">
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
                    <!-- Ambil data langsung dari Database -->
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <!-- Pakai null coalescing (??) buat jaga-jaga kalau data dari dashboard ngga ada NIS-nya -->
                        <td class="px-8 py-5 font-mono text-sm font-semibold text-slate-500">{{ $siswa->nis ?? '-' }}</td>
                        <td class="px-6 py-5 font-bold text-slate-900">{{ $siswa->nama_siswa }}</td>
                        <td class="px-6 py-5">{{ $siswa->kelas }}</td>
                        <td class="px-6 py-5 text-center">
                            @if($siswa->status == 'Bebas Tanggungan')
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-md text-xs font-bold border border-emerald-200/60">Bebas Tanggungan</span>
                            @else
                                <span class="bg-amber-50 text-amber-600 px-3 py-1.5 rounded-md text-xs font-bold border border-amber-200/60">{{ $siswa->status }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2">
                            <button onclick="editSiswa({{ $siswa->id }}, '{{ $siswa->nis }}', '{{ $siswa->nama_siswa }}', '{{ $siswa->kelas }}')" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button onclick="hapusSiswa({{ $siswa->id }}, '{{ $siswa->nama_siswa }}')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada data siswa yang terdaftar.</td>
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

    // FUNGSI TAMBAH SISWA
    function tambahSiswa() {
        customSwal.fire({
            title: 'Tambah Siswa Baru',
            html: `
                <div class="space-y-4 mt-4">
                    <input id="nis" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="NIS (Opsional)">
                    <input id="nama_siswa" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Nama Lengkap">
                    <input id="kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Kelas / Jurusan (Cth: XII - RPL)">
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Simpan Data',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return {
                    nis: document.getElementById('nis').value,
                    nama_siswa: document.getElementById('nama_siswa').value,
                    kelas: document.getElementById('kelas').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/siswa', {
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

    // FUNGSI EDIT SISWA
    function editSiswa(id, nis, nama, kelas) {
        customSwal.fire({
            title: 'Edit Data Siswa',
            html: `
                <div class="space-y-4 mt-4">
                    <input id="edit_nis" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${nis}" placeholder="NIS">
                    <input id="edit_nama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${nama}" placeholder="Nama Lengkap">
                    <input id="edit_kelas" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" value="${kelas}" placeholder="Kelas / Jurusan">
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update Data',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                return {
                    nis: document.getElementById('edit_nis').value,
                    nama_siswa: document.getElementById('edit_nama').value,
                    kelas: document.getElementById('edit_kelas').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/siswa/${id}`, {
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

    // FUNGSI HAPUS SISWA
    function hapusSiswa(id, nama) {
        customSwal.fire({
            title: 'Yakin mau dihapus?',
            html: `Data <b>${nama}</b> akan dihapus permanen dari sistem.`,
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
                fetch(`/siswa/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                }).then(() => window.location.reload());
            }
        });
    }
</script>
@endsection