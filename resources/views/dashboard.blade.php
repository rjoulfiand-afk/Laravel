<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman SMKN 10 Surabaya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { laravel: '#FF2D20', laravelDark: '#cc2419' } 
                } 
            }
        }
    </script>
    <style>
        /* Efek scrollbar rapi untuk tabel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased overflow-x-hidden flex">

    <!-- Sidebar Modern -->
    <aside class="w-64 bg-white border-r border-slate-200/60 h-screen fixed top-0 left-0 flex flex-col z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-20 flex items-center px-8 border-b border-slate-100/80">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel Logo" class="h-7 w-auto mr-3 drop-shadow-sm">
            <span class="text-xl font-bold text-slate-900 tracking-tight">Laravel</span>
        </div>
        
        <div class="px-6 pt-6 pb-2">
            <button class="w-full bg-gradient-to-r from-laravel to-laravelDark text-white rounded-xl font-semibold py-3 shadow-[0_4px_12px_rgba(255,45,32,0.25)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                <i data-feather="plus" class="w-4 h-4"></i> Pinjam Baru
            </button>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1">
            <div class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 mt-2">Menu Utama</div>
            
            <!-- Active Menu (Aksen Garis Kiri) -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-red-50 to-transparent border-l-4 border-laravel text-laravel rounded-r-xl font-medium transition-all">
                <i data-feather="grid" class="w-5 h-5"></i> Dashboard
            </a>
            
            <!-- Inactive Menus -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-r-xl font-medium transition-all">
                <i data-feather="box" class="w-5 h-5"></i> Data Barang
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-r-xl font-medium transition-all">
                <i data-feather="repeat" class="w-5 h-5"></i> Transaksi
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-r-xl font-medium transition-all">
                <i data-feather="users" class="w-5 h-5"></i> Data Siswa
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100/80">
            <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-red-50 hover:text-laravel rounded-xl font-medium transition-colors group">
                <i data-feather="log-out" class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="ml-64 flex-1 min-h-screen flex flex-col">
        
        <!-- Header Glassmorphism -->
        <header class="h-20 bg-white/70 backdrop-blur-lg border-b border-slate-200/50 sticky top-0 z-10 flex items-center justify-between px-10">
            <div class="flex items-center bg-white border border-slate-200/80 shadow-sm rounded-full px-5 py-2.5 w-[26rem] focus-within:ring-4 focus-within:ring-laravel/10 focus-within:border-laravel transition-all">
                <i data-feather="search" class="w-4 h-4 text-slate-400 mr-3"></i>
                <input type="text" placeholder="Cari barang, ID transaksi, atau nama siswa..." class="bg-transparent border-none outline-none w-full text-sm text-slate-700 placeholder-slate-400">
            </div>

            <div class="flex items-center gap-6">
                <button class="text-slate-400 hover:text-laravel transition-colors relative bg-white p-2.5 rounded-full border border-slate-200/80 shadow-sm">
                    <i data-feather="bell" class="w-5 h-5"></i>
                    <span class="absolute top-0 right-0 w-3 h-3 bg-laravel rounded-full border-2 border-white animate-pulse"></span>
                </button>
                
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 cursor-pointer hover:opacity-80 transition-opacity">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800 tracking-tight">{{ session('nama_user', 'Rixsan Joulfiand') }}</p>
                        <p class="text-[11px] font-semibold text-laravel uppercase tracking-wider">{{ session('role_user', 'Super Admin') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-laravel to-red-400 text-white flex items-center justify-center font-bold shadow-md ring-2 ring-white">
                        RJ
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Setup -->
        <div class="p-10 max-w-7xl mx-auto w-full">
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Overview Interaktif</h1>
                    <p class="text-sm text-slate-500 font-medium">Pantau lalu lintas peminjaman aset SMKN 10 Surabaya hari ini.</p>
                </div>
                <div class="text-sm font-medium text-slate-500 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-2">
                    <i data-feather="calendar" class="w-4 h-4 text-laravel"></i> 1 September 2026
                </div>
            </div>

            <!-- Stats Cards with Hover Elevation -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-1 hover:shadow-[0_8px_25px_-4px_rgba(0,0,0,0.1)] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-semibold tracking-wide">Total Aset</h3>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i data-feather="package" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="flex items-end justify-between">
                        <p class="text-4xl font-extrabold text-slate-900">248</p>
                        <p class="text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded-md flex items-center gap-1">
                            <i data-feather="arrow-up-right" class="w-3 h-3"></i> 5%
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-1 hover:shadow-[0_8px_25px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full blur-2xl opacity-60"></div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <h3 class="text-slate-500 text-sm font-semibold tracking-wide">Sedang Dipinjam</h3>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shadow-inner">
                            <i data-feather="clock" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-slate-900 relative z-10">32</p>
                    <p class="text-xs text-slate-400 font-medium mt-2 relative z-10">Menunggu pengembalian</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-1 hover:shadow-[0_8px_25px_-4px_rgba(0,0,0,0.1)] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-semibold tracking-wide">Barang Tersedia</h3>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center shadow-inner">
                            <i data-feather="check-circle" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-slate-900">216</p>
                    <p class="text-xs text-slate-400 font-medium mt-2">Tersedia di gudang</p>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:-translate-y-1 hover:shadow-[0_8px_25px_-4px_rgba(0,0,0,0.1)] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-semibold tracking-wide">Peminjam Aktif</h3>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner">
                            <i data-feather="users" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <div class="flex items-end justify-between">
                        <p class="text-4xl font-extrabold text-slate-900">18</p>
                        <p class="text-xs text-purple-600 font-bold bg-purple-50 px-2 py-1 rounded-md">
                            Siswa
                        </p>
                    </div>
                </div>

            </div>

            <!-- Table Section with Modern SaaS Styling -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight">Peminjaman Terbaru</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Log aktivitas peminjaman barang ter-update.</p>
                    </div>
                    <button class="text-sm font-semibold text-laravel hover:text-white border border-laravel hover:bg-laravel px-4 py-2 rounded-lg transition-all shadow-sm">
                        Lihat Semua
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 border-collapse">
                        <thead class="bg-slate-50/80 text-[11px] uppercase text-slate-500 font-bold tracking-wider border-b border-slate-200/60">
                            <tr>
                                <th class="px-8 py-4">Data Peminjam</th>
                                <th class="px-6 py-4">Aset Dipinjam</th>
                                <th class="px-6 py-4">Tgl Pinjam</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            
                            <!-- Row 1 -->
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td class="px-8 py-5 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-indigo-50 flex items-center justify-center text-sm font-bold text-indigo-600 border border-indigo-100">AD</div>
                                    <div>
                                        <span class="block font-bold text-slate-900">Andi Darmawan</span>
                                        <span class="block text-xs text-slate-400 font-medium mt-0.5">XI RPL 2</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 font-medium text-slate-700">Proyektor Epson EB-X05</td>
                                <td class="px-6 py-5 text-slate-500 font-medium">01 Sep 2026</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 px-3 py-1.5 rounded-full text-xs font-bold border border-amber-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Dipinjam
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button class="text-slate-400 hover:text-laravel transition-colors p-2 hover:bg-red-50 rounded-lg"><i data-feather="more-horizontal" class="w-5 h-5"></i></button>
                                </td>
                            </tr>
                            
                            <!-- Row 2 -->
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <td class="px-8 py-5 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-100 to-emerald-50 flex items-center justify-center text-sm font-bold text-emerald-600 border border-emerald-100">SN</div>
                                    <div>
                                        <span class="block font-bold text-slate-900">Siti Nurbaya</span>
                                        <span class="block text-xs text-slate-400 font-medium mt-0.5">X TKJ 1</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 font-medium text-slate-700">Kamera DSLR Canon 1300D</td>
                                <td class="px-6 py-5 text-slate-500 font-medium">31 Agu 2026</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-full text-xs font-bold border border-emerald-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Dikembalikan
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button class="text-slate-400 hover:text-laravel transition-colors p-2 hover:bg-red-50 rounded-lg"><i data-feather="more-horizontal" class="w-5 h-5"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        feather.replace();
    </script>
</body>
</html>