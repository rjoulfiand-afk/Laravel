<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Menambahkan warna merah custom khas Laravel -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        laravel: '#FF2D20',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#fdfbfb] flex items-center justify-center h-screen font-sans relative">
    
    <!-- Header / Logo di Pojok Kiri Atas -->
    <div class="absolute top-0 left-0 w-full bg-white border-b border-gray-100 px-8 py-4 flex items-center shadow-sm">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="Laravel Logo" class="h-8 w-auto mr-3">
        <span class="text-2xl font-bold text-laravel tracking-wider">Laravel</span>
    </div>

    <!-- Form Login Container -->
    <div class="bg-white p-8 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] w-full max-w-md border border-gray-100 mt-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Portal Login</h2>
            <p class="text-sm text-gray-500 mt-2">Sistem Peminjaman Barang<br><span class="font-semibold text-laravel">SMKN 10 Surabaya</span></p>
        </div>

        <form action="#" method="POST">
            
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email / Username
                </label>
                <input class="shadow-sm appearance-none border border-gray-200 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-laravel focus:border-transparent transition" id="email" type="text" placeholder="Masukkan email Anda">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow-sm appearance-none border border-gray-200 rounded-lg w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-laravel focus:border-transparent transition" id="password" type="password" placeholder="*****">
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center cursor-pointer">
                    <!-- Checkbox aksen merah -->
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-laravel focus:ring-laravel border-gray-300 rounded transition duration-150 ease-in-out accent-laravel">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                <a class="inline-block align-baseline font-bold text-sm text-laravel hover:text-red-700 transition" href="#">
                    Lupa Password?
                </a>
            </div>
            
            <div>
                <button class="w-full bg-laravel hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 ease-in-out transform hover:-translate-y-1" type="button">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-400 text-xs">
                &copy; 2026 Rixsan Joulfiand. All rights reserved.
            </p>
        </div>
    </div>

</body>
</html>