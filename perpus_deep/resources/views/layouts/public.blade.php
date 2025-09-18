<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Informasi Perpustakaan')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon (opsional, jika Anda ingin menggunakan ikon eksternal) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts untuk Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            /* Smooth scrolling for anchor links */
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Custom Keyframes for subtle animations, matching index.blade.php's feel */
        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.6s ease-out forwards;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        .delay-150 {
            animation-delay: 0.15s;
        }

        /* Adjust as needed */
    </style>
    @yield('head_extra') {{-- Untuk menambahkan CSS/JS tambahan per halaman --}}
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-700 p-4 text-white shadow-xl sticky top-0 z-50"> {{-- Updated gradient and stronger shadow --}}
        <div class="container mx-auto flex justify-between items-center flex-wrap">
            <a href="{{ route('public.home') }}" class="text-3xl font-extrabold hover:text-gray-100 transition-colors duration-200">PerpusKu</a> {{-- Larger font and bolder --}}
            <div class="space-x-4 flex items-center mt-2 md:mt-0">
                <a href="{{ route('public.home') }}" class="px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-300 transform hover:scale-105">Beranda</a> {{-- Added padding, rounded, and hover effects --}}
                <a href="{{ route('public.books') }}" class="px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-300 transform hover:scale-105">Daftar Buku</a>
                <a href="{{ route('public.categories') }}" class="px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-300 transform hover:scale-105">Kategori</a>
                <a href="{{ route('public.about') }}" class="px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-300 transform hover:scale-105">Tentang Kami</a>
                <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-white text-teal-600 font-semibold hover:bg-gray-100 transition duration-300 shadow-md transform hover:scale-110 ml-4">Login Admin</a> {{-- Larger padding, fully rounded, prominent --}}
            </div>
        </div>
    </nav>

    <!-- Content Section (where specific page content will be injected) -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-slate-800 text-white p-6 md:p-8 mt-12 shadow-inner"> {{-- Updated gradient and padding --}}
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left">
            <div class="mb-4 md:mb-0">
                <p>&copy; {{ date('Y') }} Portal Informasi Perpustakaan. Hak Cipta Dilindungi.</p>
                <p class="text-sm opacity-80 mt-1">Dikelola dengan <i class="fas fa-heart text-red-500"></i> dan Laravel.</p>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-300 transition-colors duration-200 transform hover:scale-110" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a> {{-- Added hover scale --}}
                <a href="#" class="hover:text-gray-300 transition-colors duration-200 transform hover:scale-110" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-gray-300 transition-colors duration-200 transform hover:scale-110" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-gray-300 transition-colors duration-200 transform hover:scale-110" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </footer>

</body>

</html>