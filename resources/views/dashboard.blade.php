<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman SMKN 10 Surabaya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Feather Icons untuk icon yang minimalis dan rapi -->
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
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 h-screen fixed top-0 left-0 flex flex-col z-20">
        <div class="h-20 flex items-center px-8 border-b border-slate-100">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel Logo" class="h-8 w-auto mr-3">
            <span class="text-xl font-bold text-laravel tracking-wider">Laravel</span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Active Menu -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-red-50 text-laravel rounded-xl font-medium transition-colors">
                <i data-feather="grid" class="w-5 h-5"></i>
                Dashboard
            </a>
            <!-- Inactive Menus -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-feather="box" class="w-5 h-5"></i>
                Data Barang
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-feather="repeat" class="w-5 h-5"></i>
                Peminjaman
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-feather="users" class="w-5 h-5"></i>
                Data Siswa
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-colors">
                <i data-feather="file-text" class="w-5 h-5"></i>
                Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-red-50 hover:text-laravel rounded-xl font-medium transition-colors">
                <i data-feather="log-out" class="w-5 h-5"></i>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="ml-64 flex-1 min-h-screen flex flex-col">
        
        <!-- Header -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/50 sticky top-0 z-10 flex items-center justify-between px-8">
            <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 w-96 focus-within:ring-2 focus-within:ring-laravel/30 focus-within:border-laravel transition-all">
                <i data-feather="search" class="w-4 h-4 text-slate-400 mr-3"></i>
                <input type="text" placeholder="Cari barang atau peminjam..." class="bg-transparent border-none outline-none w-full text-sm text-slate-700">
            </div>

            <div class="flex items-center gap-6">
                <button class="text-slate-400 hover:text-laravel transition-colors relative">
                    <i data-feather="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-laravel rounded-full border-2 border-white"></span>
                </button>
                
                <div class="flex items-center gap-3 border-l border-slate-200 pl-6 cursor-pointer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-semibold text-slate-800">{{ session('nama_user') }}</p>
                        <p class="text-xs text-laravel font-bold">{{ session('role_user') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-laravel to-laravelDark text-white flex items-center justify-center font-bold shadow-sm">
                        RJ
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Ringkasan Inventaris</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau status barang dan aktivitas peminjaman hari ini.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-medium">Total Barang</h3>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i data-feather="package" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900">248</p>
                    <p class="text-xs text-emerald-500 font-medium mt-2 flex items-center gap-1">
                        <i data-feather="trending-up" class="w-3 h-3"></i> +12 bulan ini
                    </p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-medium">Sedang Dipinjam</h3>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i data-feather="clock" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900">32</p>
                    <p class="text-xs text-slate-400 font-medium mt-2">Menunggu pengembalian</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-medium">Barang Tersedia</h3>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-feather="check-circle" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900">216</p>
                    <p class="text-xs text-slate-400 font-medium mt-2">Siap untuk dipinjam</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-500 text-sm font-medium">Peminjam Aktif</h3>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i data-feather="user" class="w-5 h-5"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900">18</p>
                    <p class="text-xs text-slate-400 font-medium mt-2">Siswa SMKN 10 Surabaya</p>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Peminjaman Terbaru</h3>
                    <a href="#" class="text-sm font-medium text-laravel hover:text-laravelDark">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/50 text-xs uppercase text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Peminjam</th>
                                <th class="px-6 py-4">Barang</th>
                                <th class="px-6 py-4">Tgl Pinjam</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">AD</div>
                                    <span class="font-medium text-slate-900">Andi Darmawan</span>
                                </td>
                                <td class="px-6 py-4">Proyektor Epson EB-X05</td>
                                <td class="px-6 py-4">01 Sep 2026</td>
                                <td class="px-6 py-4">
                                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-medium border border-amber-200/50">Dipinjam</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-900 transition-colors"><i data-feather="more-vertical" class="w-4 h-4"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">SN</div>
                                    <span class="font-medium text-slate-900">Siti Nurbaya</span>
                                </td>
                                <td class="px-6 py-4">Kamera DSLR Canon 1300D</td>
                                <td class="px-6 py-4">31 Agu 2026</td>
                                <td class="px-6 py-4">
                                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-medium border border-emerald-200/50">Dikembalikan</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-slate-900 transition-colors"><i data-feather="more-vertical" class="w-4 h-4"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Render Icons -->
    <script>
        feather.replace();
    </script>
</body>
</html>