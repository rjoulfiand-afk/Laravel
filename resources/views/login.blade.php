<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Peminjaman Barang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { laravel: '#FF2D20', laravelDark: '#cc2419' } 
                } 
            }
        }
    </script>
    <style>
        /* Kontrol Kecepatan & Transisi Elegan (0.9 detik) */
        .container-panel, .form-container, .overlay-container, .overlay, .overlay-left, .overlay-right {
            transition: all 0.9s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .container-panel.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; z-index: 1; }
        .container-panel.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.9s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .container-panel.right-panel-active .overlay-container { transform: translateX(-100%); }
        .container-panel.right-panel-active .overlay { transform: translateX(50%); }
        .container-panel.right-panel-active .overlay-left { transform: translateX(0); }
        .container-panel.right-panel-active .overlay-right { transform: translateX(20%); opacity: 0; }
        
        .overlay-container { border-top-left-radius: 80px; border-bottom-left-radius: 80px; }
        .container-panel.right-panel-active .overlay-container {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 80px;
            border-bottom-right-radius: 80px;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; transform: translateX(50%); }
            50%, 100% { opacity: 1; z-index: 5; transform: translateX(100%); }
        }
    </style>
</head>
<body class="bg-[#f4f7f6] text-gray-800 font-sans antialiased overflow-x-hidden">
    
    <!-- Navbar Atas (Menu dan Badge Kanan Dihapus) -->
    <nav class="fixed top-0 left-0 w-full bg-white shadow-sm z-50 px-8 py-4 flex items-center">
        <div class="flex items-center cursor-pointer hover:opacity-80 transition">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel Logo" class="h-8 w-auto mr-3">
            <span class="text-xl font-bold text-laravel tracking-wider">Laravel</span>
        </div>
    </nav>

    <!-- Wrapper Form Login -->
    <div class="min-h-screen flex items-center justify-center pt-20 pb-10 px-4">
        
        <!-- Wadah Utama -->
        <div class="container-panel relative bg-white rounded-[30px] shadow-[0_20px_50px_rgb(0,0,0,0.08)] overflow-hidden w-[850px] max-w-full min-h-[550px]" id="mainContainer">
            
            <!-- Panel Sign Up (Kiri - Tersembunyi) -->
            <div class="form-container sign-up-container absolute top-0 left-0 h-full w-1/2 opacity-0 z-1 flex flex-col justify-center px-12 bg-white">
                <form action="#" class="flex flex-col items-center justify-center h-full text-center w-full">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900">Buat Akun</h2>
                    <p class="text-sm text-gray-500 mb-8">Lengkapi data diri Anda di bawah ini</p>
                    
                    <input type="text" placeholder="Nama Lengkap" class="bg-gray-50 border border-gray-200 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-laravel/30 focus:border-laravel transition-all">
                    <input type="email" placeholder="Email" class="bg-gray-50 border border-gray-200 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-laravel/30 focus:border-laravel transition-all">
                    <input type="password" placeholder="Password" class="bg-gray-50 border border-gray-200 text-sm rounded-xl px-5 py-3.5 w-full mb-6 focus:outline-none focus:ring-2 focus:ring-laravel/30 focus:border-laravel transition-all">
                    
                    <button type="button" class="w-full bg-laravel text-white rounded-xl font-semibold py-3.5 hover:bg-laravelDark hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">Daftar Sekarang</button>
                </form>
            </div>

            <!-- Panel Sign In (Kiri - Aktif) -->
            <div class="form-container sign-in-container absolute top-0 left-0 h-full w-1/2 z-2 flex flex-col justify-center px-12 bg-white">
                <form action="#" class="flex flex-col items-center justify-center h-full text-center w-full">
                    <div class="mb-5 md:hidden">
                        <!-- Logo cadangan jika di layar HP navbar hilang -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel Logo" class="h-10 w-auto">
                    </div>
                    <h2 class="text-3xl font-bold mb-2 text-gray-900">Selamat Datang</h2>
                    <p class="text-sm text-gray-500 mb-8">Masuk untuk mengelola peminjaman barang</p>
                    
                    <input type="email" placeholder="Email / Username" class="bg-gray-50 border border-gray-200 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-laravel/30 focus:border-laravel transition-all">
                    <input type="password" placeholder="Password" class="bg-gray-50 border border-gray-200 text-sm rounded-xl px-5 py-3.5 w-full mb-2 focus:outline-none focus:ring-2 focus:ring-laravel/30 focus:border-laravel transition-all">
                    
                    <div class="w-full text-right mb-6">
                        <a href="#" class="text-xs text-gray-500 hover:text-laravel transition-colors font-medium">Lupa Password?</a>
                    </div>
                    
                    <button type="button" class="w-full bg-laravel text-white rounded-xl font-semibold py-3.5 hover:bg-laravelDark hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">Masuk ke Sistem</button>
                </form>
            </div>

            <!-- Wadah Overlay Merah -->
            <div class="overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden z-10 hidden md:block">
                <div class="overlay bg-gradient-to-br from-[#ff5b52] to-[#FF2D20] text-white relative -left-full h-full w-[200%]">
                    
                    <!-- Teks Overlay Kiri (Aktif saat Sign In) -->
                    <div class="overlay-left absolute flex flex-col items-center justify-center px-14 text-center top-0 h-full w-1/2 -translate-x-[20%]">
                        <h2 class="text-3xl font-bold mb-5 tracking-wide">Sudah Punya Akun?</h2>
                        <p class="text-sm font-light mb-8 leading-relaxed opacity-90">Jangan buang waktu, langsung masuk dan mulai pinjam fasilitas inventaris.</p>
                        <button class="bg-transparent border border-white/50 hover:bg-white hover:text-laravel text-white rounded-xl font-semibold px-12 py-3.5 transition-all duration-300" id="signInBtn">Masuk Sekarang</button>
                    </div>

                    <!-- Teks Overlay Kanan (Aktif saat Sign Up) -->
                    <div class="overlay-right absolute flex flex-col items-center justify-center px-14 text-center top-0 right-0 h-full w-1/2 translate-x-0">
                        <h2 class="text-3xl font-bold mb-5 tracking-wide">Siswa Baru?</h2>
                        <p class="text-sm font-light mb-8 leading-relaxed opacity-90">Ayo daftarkan dirimu untuk mendapatkan akses penuh ke sistem inventaris.</p>
                        <button class="bg-transparent border border-white/50 hover:bg-white hover:text-laravel text-white rounded-xl font-semibold px-12 py-3.5 transition-all duration-300" id="signUpBtn">Buat Akun</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUpBtn');
        const signInButton = document.getElementById('signInBtn');
        const mainContainer = document.getElementById('mainContainer');

        signUpButton.addEventListener('click', () => {
            mainContainer.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            mainContainer.classList.remove("right-panel-active");
        });
    </script>
</body>
</html>