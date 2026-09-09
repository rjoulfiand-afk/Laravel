<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Harian | Boss Jul</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Caveat:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS (Jalur Instan) & Alpine JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-handwriting { font-family: 'Caveat', cursive; }
        /* Sembunyiin scrollbar biar kaya App Native */
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 h-screen overflow-hidden flex flex-col">

    <!-- Area Konten Utama yang bakal ganti-ganti (Scrollable) -->
    <main class="flex-1 overflow-y-auto pb-24 p-5">
        @yield('content')
    </main>

    <!-- Bottom Navigation Bar (Glassmorphism) -->
    <nav class="fixed bottom-0 w-full px-6 py-4 bg-gray-900/80 backdrop-blur-lg border-t border-white/10 z-50">
        <ul class="flex justify-between items-center text-gray-400">
            <!-- Menu Beranda -->
            <li>
                <a href="/harian" class="flex flex-col items-center gap-1 text-violet-400">
                    <i class="fas fa-home text-xl"></i>
                    <span class="text-[10px] font-bold tracking-wider">HOME</span>
                </a>
            </li>
            <!-- Menu Uang -->
            <li>
                <a href="#" class="flex flex-col items-center gap-1 hover:text-white transition">
                    <i class="fas fa-wallet text-xl"></i>
                    <span class="text-[10px] font-bold tracking-wider">UANG</span>
                </a>
            </li>
            <!-- Menu Catatan (Tombol Tengah Gede) -->
            <li class="-mt-8">
                <a href="#" class="flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-violet-600 to-indigo-500 rounded-full text-white shadow-[0_0_20px_rgba(139,92,246,0.4)] hover:scale-110 transition-transform">
                    <i class="fas fa-pen text-xl"></i>
                </a>
            </li>
            <!-- Menu Tugas -->
            <li>
                <a href="#" class="flex flex-col items-center gap-1 hover:text-white transition">
                    <i class="fas fa-clipboard-check text-xl"></i>
                    <span class="text-[10px] font-bold tracking-wider">TUGAS</span>
                </a>
            </li>
            <!-- Menu AI -->
            <li>
                <a href="#" class="flex flex-col items-center gap-1 hover:text-white transition">
                    <i class="fas fa-robot text-xl"></i>
                    <span class="text-[10px] font-bold tracking-wider">AI</span>
                </a>
            </li>
        </ul>
    </nav>

</body>
</html>