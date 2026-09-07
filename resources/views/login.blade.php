<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventaris SMKN 10</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { 
                        primary: '#4f46e5', /* Indigo 600 */
                        secondary: '#3b82f6' /* Blue 500 */
                    } 
                } 
            }
        }
    </script>
    <style>
        .container-panel, .form-container, .overlay-container, .overlay, .overlay-left, .overlay-right {
            transition: all 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .container-panel.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; z-index: 1; }
        .container-panel.right-panel-active .sign-up-container {
            transform: translateX(100%); opacity: 1; z-index: 5;
            animation: show 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .container-panel.right-panel-active .overlay-container { transform: translateX(-100%); }
        .container-panel.right-panel-active .overlay { transform: translateX(50%); }
        .container-panel.right-panel-active .overlay-left { transform: translateX(0); }
        .container-panel.right-panel-active .overlay-right { transform: translateX(20%); opacity: 0; }
        
        .overlay-container { border-top-left-radius: 80px; border-bottom-left-radius: 80px; }
        .container-panel.right-panel-active .overlay-container {
            border-top-left-radius: 0; border-bottom-left-radius: 0;
            border-top-right-radius: 80px; border-bottom-right-radius: 80px;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; transform: translateX(50%); }
            50%, 100% { opacity: 1; z-index: 5; transform: translateX(100%); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden min-h-screen flex items-center justify-center">
    
    <!-- Navbar Minimalis -->
    <nav class="absolute top-0 left-0 w-full z-50 px-10 py-6 flex items-center justify-between pointer-events-none">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center font-bold text-xl shadow-lg shadow-primary/30 text-white">10</div>
            <div>
                <span class="text-xl font-extrabold text-slate-900 tracking-wide block leading-none">Inventaris</span>
                <span class="text-xs font-semibold text-slate-500 tracking-widest uppercase">SMKN 10 Surabaya</span>
            </div>
        </div>
    </nav>

    <div class="w-full px-4 flex justify-center z-10">
        
        <!-- Wadah Utama Putih Bersih -->
        <div class="container-panel relative bg-white rounded-[30px] shadow-[0_20px_50px_rgb(0,0,0,0.05)] overflow-hidden w-[900px] max-w-full min-h-[580px] flex border border-slate-100" id="mainContainer">
            
            <!-- Panel Sign Up -->
            <div class="form-container sign-up-container absolute top-0 left-0 h-full w-1/2 opacity-0 z-1 flex flex-col justify-center px-14 bg-white">
                <form action="#" class="flex flex-col items-center justify-center h-full text-center w-full">
                    <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Buat Akun</h2>
                    <p class="text-sm text-slate-500 mb-8">Daftarkan diri untuk meminjam barang</p>
                    
                    <input type="text" placeholder="Nama Lengkap" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all">
                    <input type="email" placeholder="Email Sekolah" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all">
                    <input type="password" placeholder="Password" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-5 py-3.5 w-full mb-8 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all">
                    
                    <button type="button" class="w-full bg-slate-900 text-white rounded-xl font-bold tracking-wide py-3.5 hover:bg-slate-800 transition-all duration-300">DAFTAR SEKARANG</button>
                </form>
            </div>

           <!-- Panel Sign In -->
            <div class="form-container sign-in-container absolute top-0 left-0 h-full w-1/2 z-2 flex flex-col justify-center px-14 bg-white">
                <form action="/dashboard" method="GET" class="flex flex-col items-center justify-center h-full text-center w-full">
                    @csrf 
                    
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 border border-slate-100 shadow-sm">
                        <i data-feather="user" class="w-8 h-8 text-primary"></i>
                    </div>
                    
                    <h2 class="text-3xl font-extrabold mb-2 text-slate-900 tracking-tight">Selamat Datang</h2>
                    <p class="text-sm text-slate-500 mb-8">Masuk untuk mengelola aset sekolah</p>
                    
                    @if(session('error'))
                        <div class="w-full bg-red-50 text-red-600 text-sm py-2.5 px-4 rounded-xl mb-6 font-medium border border-red-100">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <input type="text" name="username" placeholder="NIS / Email" required class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-5 py-3.5 w-full mb-4 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all">
                    <input type="password" name="password" placeholder="Password" required class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-5 py-3.5 w-full mb-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all">
                    
                    <div class="w-full text-right mb-8">
                        <a href="#" class="text-xs text-slate-500 hover:text-primary transition-colors font-medium">Lupa Password?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white rounded-xl font-bold tracking-wide py-3.5 shadow-[0_4px_14px_0_rgba(79,70,229,0.3)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all duration-300">MASUK</button>
                </form>
            </div>

            <!-- Wadah Overlay Gradasi (Warna persis seperti banner dashboard) -->
            <div class="overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden z-10 hidden md:block">
                <div class="overlay bg-gradient-to-r from-primary to-secondary text-white relative -left-full h-full w-[200%]">
                    
                    <!-- Background Pattern Tipis -->
                    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>

                    <!-- Teks Overlay Kiri -->
                    <div class="overlay-left absolute flex flex-col items-center justify-center px-14 text-center top-0 h-full w-1/2 -translate-x-[20%]">
                        <i data-feather="check-circle" class="w-16 h-16 text-white/80 mb-6"></i>
                        <h2 class="text-3xl font-bold mb-4 tracking-wide text-white">Sudah Terdaftar?</h2>
                        <p class="text-sm font-light mb-10 leading-relaxed text-indigo-100">Jika akunmu sudah aktif, langsung masuk untuk mengecek ketersediaan barang.</p>
                        <button class="bg-white/10 backdrop-blur-sm border border-white/30 hover:bg-white hover:text-primary text-white rounded-xl font-bold tracking-wider px-12 py-3.5 transition-all duration-300" id="signInBtn">MASUK</button>
                    </div>

                    <!-- Teks Overlay Kanan -->
                    <div class="overlay-right absolute flex flex-col items-center justify-center px-14 text-center top-0 right-0 h-full w-1/2 translate-x-0">
                        <i data-feather="box" class="w-16 h-16 text-white/80 mb-6"></i>
                        <h2 class="text-3xl font-bold mb-4 tracking-wide text-white">Siswa Baru?</h2>
                        <p class="text-sm font-light mb-10 leading-relaxed text-indigo-100">Daftarkan dirimu sekarang untuk mendapatkan akses peminjaman fasilitas sekolah.</p>
                        <button class="bg-white/10 backdrop-blur-sm border border-white/30 hover:bg-white hover:text-primary text-white rounded-xl font-bold tracking-wider px-12 py-3.5 transition-all duration-300" id="signUpBtn">BUAT AKUN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        
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