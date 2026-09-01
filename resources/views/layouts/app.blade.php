<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { laravel: '#FF2D20', laravelDark: '#cc2419' } } }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased overflow-x-hidden flex">

    <!-- Sidebar Master -->
    <aside class="w-64 bg-white border-r border-slate-200/60 h-screen fixed top-0 left-0 flex flex-col z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-20 flex items-center px-8 border-b border-slate-100/80">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel" class="h-7 w-auto mr-3">
            <span class="text-xl font-bold text-slate-900 tracking-tight">Inventaris</span>
        </div>
        <div class="px-6 pt-6 pb-2">
            <button class="w-full bg-gradient-to-r from-laravel to-laravelDark text-white rounded-xl font-semibold py-3 shadow-[0_4px_12px_rgba(255,45,32,0.25)] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                <i data-feather="plus" class="w-4 h-4"></i> Pinjam Baru
            </button>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1">
            <div class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 mt-2">Menu Utama</div>
            
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium transition-all {{ Request::is('dashboard') ? 'bg-gradient-to-r from-red-50 to-transparent border-l-4 border-laravel text-laravel' : 'border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i data-feather="grid" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="/barang" class="flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium transition-all {{ Request::is('barang') ? 'bg-gradient-to-r from-red-50 to-transparent border-l-4 border-laravel text-laravel' : 'border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i data-feather="box" class="w-5 h-5"></i> Data Barang
            </a>
            <a href="/transaksi" class="flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium transition-all {{ Request::is('transaksi') ? 'bg-gradient-to-r from-red-50 to-transparent border-l-4 border-laravel text-laravel' : 'border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i data-feather="repeat" class="w-5 h-5"></i> Transaksi
            </a>
            <a href="/siswa" class="flex items-center gap-3 px-4 py-3 rounded-r-xl font-medium transition-all {{ Request::is('siswa') ? 'bg-gradient-to-r from-red-50 to-transparent border-l-4 border-laravel text-laravel' : 'border-l-4 border-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i data-feather="users" class="w-5 h-5"></i> Data Siswa
            </a>
        </nav>
        <div class="p-4 border-t border-slate-100/80">
            <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-red-50 hover:text-laravel rounded-xl font-medium transition-colors group">
                <i data-feather="log-out" class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 min-h-screen flex flex-col">
        <!-- Header Master -->
        <header class="h-20 bg-white/70 backdrop-blur-lg border-b border-slate-200/50 sticky top-0 z-10 flex items-center justify-between px-10">
            <div class="flex items-center bg-white border border-slate-200/80 shadow-sm rounded-full px-5 py-2.5 w-[26rem] focus-within:ring-4 focus-within:ring-laravel/10 focus-within:border-laravel transition-all">
                <i data-feather="search" class="w-4 h-4 text-slate-400 mr-3"></i>
                <input type="text" placeholder="Pencarian cepat..." class="bg-transparent border-none outline-none w-full text-sm text-slate-700">
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 pl-6 border-l border-slate-200 cursor-pointer hover:opacity-80 transition-opacity">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-slate-800">{{ session('nama_user') }}</p>
                        <p class="text-[11px] font-semibold text-laravel uppercase tracking-wider">{{ session('role_user') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-laravel to-red-400 text-white flex items-center justify-center font-bold shadow-md ring-2 ring-white">RJ</div>
                </div>
            </div>
        </header>

        <!-- AREA KONTEN DINAMIS -->
        @yield('content')

    </main>

    <script> feather.replace(); </script>
</body>
</html>