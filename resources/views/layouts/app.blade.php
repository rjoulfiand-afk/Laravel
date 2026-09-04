<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SMKN 10 SBY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    
    <!-- Sidebar Kiri -->
    <aside class="w-72 bg-slate-900 text-white flex flex-col transition-all duration-300">
        <div class="h-20 flex items-center px-8 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center font-bold text-xl shadow-lg shadow-indigo-500/30">
                    10
                </div>
                <div>
                    <h2 class="font-bold text-lg tracking-wide">Inventaris</h2>
                    <p class="text-xs text-slate-400 font-medium">SMKN 10 Surabaya</p>
                </div>
            </div>
        </div>
        
        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 rounded-xl text-white font-medium shadow-md shadow-indigo-600/20 transition-all">
                <i data-feather="grid" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="/barang" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl font-medium transition-all">
                <i data-feather="box" class="w-5 h-5"></i> Data Barang
            </a>
            <a href="/siswa" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl font-medium transition-all">
                <i data-feather="users" class="w-5 h-5"></i> Data Siswa
            </a>
            <a href="/peminjaman" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl font-medium transition-all">
                <i data-feather="repeat" class="w-5 h-5"></i> Peminjaman
            </a>
        </nav>
        
        <!-- Profil Admin Bawah -->
        <div class="p-6 border-t border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-700 border-2 border-indigo-500 flex items-center justify-center overflow-hidden">
                    <i data-feather="user" class="w-5 h-5 text-slate-300"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Rixsan J.</p>
                    <p class="text-xs text-slate-400">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Area Konten Kanan -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-10">
            <h2 class="text-slate-800 font-bold text-lg">@yield('title')</h2>
            <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
                <i data-feather="bell" class="w-5 h-5"></i>
            </button>
        </header>
        
        <!-- Isi Halaman (Dinamis) -->
        <div class="flex-1 overflow-y-auto">
            @yield('content')
        </div>
    </main>

    <!-- Render Ikon -->
    <script>feather.replace();</script>
</body>
</html>