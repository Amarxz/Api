@extends('layouts.public')

@section('title', 'Beranda - Portal Informasi Perpustakaan')

@section('content')
<!-- Hero Section with Gradient & Floating Elements -->
<header class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-white/10 rounded-full blur-2xl animate-bounce"></div>
    </div>

    <!-- Floating Books Animation -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 text-white/20 text-6xl animate-float">📚</div>
        <div class="absolute top-3/4 right-1/4 text-white/20 text-5xl animate-float-delayed">📖</div>
        <div class="absolute top-1/2 right-1/3 text-white/20 text-4xl animate-float-slow">✨</div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center px-6 max-w-5xl mx-auto">
        <div class="backdrop-blur-sm bg-white/10 rounded-3xl p-8 md:p-12 border border-white/20 shadow-2xl">
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-black mb-6 text-white drop-shadow-2xl leading-tight">
                <span class="bg-gradient-to-r from-white to-cyan-100 bg-clip-text text-transparent">
                    Jelajahi Dunia
                </span>
                <br>
                <span class="text-cyan-100 animate-pulse">Pengetahuan</span>
            </h1>
            <p class="text-xl md:text-2xl lg:text-3xl mb-10 text-cyan-50 font-light leading-relaxed">
                Temukan koleksi buku, jurnal, dan sumber daya lainnya<br>
                di perpustakaan digital masa depan
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.books') }}"
                    class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-teal-600 bg-white rounded-full overflow-hidden shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-white/25">
                    <span class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span class="relative group-hover:text-white transition-colors duration-300">
                        🚀 Mulai Eksplorasi
                    </span>
                </a>
                <a href="{{ route('public.about') }}"
                    class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white/30 rounded-full backdrop-blur-sm hover:bg-white/10 transition-all duration-300 hover:scale-105">
                    <span class="mr-2">📋</span>
                    Layanan Kami
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white/70 rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</header>

<!-- Services Section with Glass Morphism -->
<section id="services" class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-5xl md:text-6xl font-black text-gray-800 mb-4">
                <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Layanan
                </span>
                <span class="text-gray-700">Unggulan</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Nikmati pengalaman literasi digital yang tak terbatas dengan berbagai fasilitas modern
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <!-- Service 1 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-3xl blur-xl opacity-0 group-hover:opacity-20 transition-all duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-gray-200/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        📚
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Koleksi Digital</h3>
                    <p class="text-gray-600 text-center leading-relaxed mb-6">
                        Akses ribuan e-book, jurnal ilmiah, dan majalah digital dari seluruh dunia dengan teknologi pencarian AI
                    </p>
                    <div class="text-center">
                        <span class="inline-flex items-center text-teal-600 font-semibold group-hover:text-emerald-600 transition-colors">
                            Jelajahi Sekarang
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 rounded-3xl blur-xl opacity-0 group-hover:opacity-20 transition-all duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-gray-200/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🗺️
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Akses 24/7</h3>
                    <p class="text-gray-600 text-center leading-relaxed mb-6">
                        Perpustakaan digital yang dapat diakses kapan saja, di mana saja dengan sistem reservasi online yang mudah
                    </p>
                    <div class="text-center">
                        <span class="inline-flex items-center text-purple-600 font-semibold group-hover:text-pink-600 transition-colors">
                            Cek Lokasi
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-400 rounded-3xl blur-xl opacity-0 group-hover:opacity-20 transition-all duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-gray-200/50 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        👥
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Komunitas Aktif</h3>
                    <p class="text-gray-600 text-center leading-relaxed mb-6">
                        Bergabung dengan klub baca, webinar literasi, dan diskusi virtual bersama para penulis terkenal
                    </p>
                    <div class="text-center">
                        <span class="inline-flex items-center text-orange-600 font-semibold group-hover:text-red-600 transition-colors">
                            Gabung Komunitas
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-5xl md:text-6xl font-black text-gray-800 mb-4">
                <span class="text-gray-700">Koleksi</span>
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    Terbaru
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Temukan buku-buku pilihan terbaru yang akan memperkaya wawasan dan pengetahuan Anda
            </p>
        </div>

        @if($latestBooks->count() > 0)
        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($latestBooks as $book)
            <div class="group relative">
                <!-- Glow Effect -->
                <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-2xl blur opacity-0 group-hover:opacity-30 transition-all duration-500"></div>

                <!-- Book Card -->
                <div class="relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group-hover:-translate-y-2">
                    <!-- Book Cover -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $book['cover_url'] }}"
                            alt="Sampul {{ $book['title'] }}"
                            class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Floating Badge -->
                        <div class="absolute top-4 right-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                            Baru
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-teal-600 transition-colors">
                            {{ $book['title'] }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-1">oleh</p>
                        <p class="text-emerald-600 font-semibold mb-4">{{ $book['author'] }}</p>

                        <!-- Action Button -->
                        <a href="{{ route('public.book.detail', $book['id']) }}"
                            class="group/btn relative w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/25">
                            <span class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <span class="mr-2">📖</span>
                                Lihat Detail
                                <svg class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-16">
            <a href="{{ route('public.books') }}"
                class="group relative inline-flex items-center justify-center px-12 py-4 text-lg font-bold bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-full overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <span class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative flex items-center">
                    <span class="mr-3">📚</span>
                    Jelajahi Semua Koleksi
                    <svg class="ml-3 w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </span>
            </a>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center text-6xl">
                    📚
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-4">Koleksi Segera Hadir</h3>
                <p class="text-gray-600 mb-8">
                    Kami sedang menyiapkan koleksi buku terbaru yang menarik untuk Anda.
                    Pantau terus untuk update terbaru!
                </p>
                <button class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition-all duration-300">
                    Notify Me
                </button>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Custom Animations CSS -->
<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(5deg);
        }
    }

    @keyframes float-delayed {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(-3deg);
        }
    }

    @keyframes float-slow {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-10px) rotate(2deg);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-float-delayed {
        animation: float-delayed 8s ease-in-out infinite 2s;
    }

    .animate-float-slow {
        animation: float-slow 10s ease-in-out infinite 1s;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-clamp: 2;
        /* Standard property for future compatibility */
        overflow: hidden;
    }
</style>
@endsection