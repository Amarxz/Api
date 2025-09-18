<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PerpusDeep Admin</title>
    <!-- Tailwind CSS CDN untuk styling cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts untuk Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Memastikan font Inter digunakan secara global */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Styling responsif untuk navigasi mobile */
        @media (max-width: 767px) {
            .nav-links-container {
                flex-direction: column;
                /* Mengganti flex-col */
                gap: 0.5rem;
                /* Mengganti space-y-2 */
                width: 100%;
                margin-top: 1rem;
            }

            .nav-links-container a,
            .nav-links-container form {
                width: 100%;
                text-align: left;
                margin-right: 0 !important;
                /* Hapus margin kanan */
            }

            .nav-links-container form button {
                width: 100%;
                text-align: left;
            }
        }
    </style>
    @yield('head') {{-- Area untuk head section dari blade files lain --}}
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal flex flex-col min-h-screen">
    <nav class="bg-gray-800 p-4 text-white shadow-md">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold mb-2 md:mb-0">PerpusDeep Admin</a>
            <div class="flex flex-wrap md:flex-row md:space-x-4 items-center nav-links-container">
                {{-- Navigasi utama untuk modul admin --}}
                <a href="{{ route('bukus.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Buku</a>
                <a href="{{ route('kategoris.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Kategori</a>
                <a href="{{ route('users.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Pengguna</a>
                <a href="{{ route('peminjaman.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Peminjaman</a>
                <a href="{{ route('pemesanan.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Pemesanan</a>
                <a href="{{ route('notifikasis.index') }}" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Notifikasi</a>

                {{-- Link ke Portal Informasi Publik (Web Kedua) --}}
                <a href="{{ route('public.home') }}" class="px-2 py-1 bg-teal-600 text-white hover:bg-teal-700 transition-colors duration-200 rounded-md font-semibold md:ml-4">
                    Lihat Portal Publik
                </a>

                {{-- Form Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="inline md:ml-4">
                    @csrf
                    <button type="submit" class="px-2 py-1 hover:text-gray-300 transition-colors duration-200 rounded">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4 flex-grow">
        {{-- Flash Messages (untuk notifikasi sukses/error) --}}
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Konten halaman spesifik akan diinjeksi di sini --}}
        @yield('content')
    </main>

    {{-- Footer yang Bagus dan Menarik --}}
    <footer class="bg-gray-800 text-white p-6 mt-8 shadow-inner">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left">
            <div class="mb-4 md:mb-0">
                <p>&copy; {{ date('Y') }} PerpusDeep Admin. All rights reserved.</p>
                <p class="text-sm opacity-80 mt-1">Dikelola dengan <i class="fas fa-heart text-red-500"></i> dan Laravel.</p>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-300 transition-colors duration-200" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-gray-300 transition-colors duration-200" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-gray-300 transition-colors duration-200" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-gray-300 transition-colors duration-200" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </footer>
</body>

</html>