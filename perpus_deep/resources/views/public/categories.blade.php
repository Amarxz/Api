@extends('layouts.public')

@section('title', 'Kategori Buku - Portal Informasi Perpustakaan')

@section('content')
<!-- Hero Section with Gradient -->
<header class="relative py-20 bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-white/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-bounce"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 text-white/20 text-4xl animate-float">📚</div>
        <div class="absolute top-3/4 right-1/4 text-white/20 text-3xl animate-float-delayed">🏷️</div>
        <div class="absolute top-1/2 right-1/3 text-white/20 text-2xl animate-float-slow">✨</div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 container mx-auto px-6 text-center">
        <div class="backdrop-blur-sm bg-white/10 rounded-3xl p-8 md:p-12 border border-white/20 shadow-2xl max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 text-white drop-shadow-2xl leading-tight">
                <span class="bg-gradient-to-r from-white to-cyan-100 bg-clip-text text-transparent">
                    Jelajahi
                </span>
                <br>
                <span class="text-cyan-100 animate-pulse">Kategori Buku</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-cyan-50 font-light leading-relaxed">
                Temukan buku favorit Anda berdasarkan kategori pilihan<br>
                yang telah kami kurasi dengan cermat
            </p>
        </div>
    </div>
</header>

<!-- Search Section (Optional) -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-2xl blur opacity-0 group-hover:opacity-20 transition-all duration-300"></div>
                <div class="relative bg-white rounded-2xl border-2 border-gray-200 group-hover:border-teal-300 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                    <div class="flex items-center p-4">
                        <div class="flex-shrink-0 pl-4">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                            placeholder="Cari kategori buku..."
                            class="flex-1 px-4 py-2 text-gray-700 bg-transparent border-none outline-none text-lg placeholder-gray-400">
                        <button class="flex-shrink-0 bg-gradient-to-r from-teal-500 to-emerald-500 text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notification Messages -->
@if (session('error'))
<div class="container mx-auto px-6 py-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-red-700 font-medium">Error!</p>
                    <p class="text-red-600">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (session('success'))
<div class="container mx-auto px-6 py-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-green-700 font-medium">Sukses!</p>
                    <p class="text-green-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Categories Section -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-6">
        @if ($categories->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center text-6xl animate-pulse">
                    🏷️
                </div>
                <h3 class="text-3xl font-bold text-gray-700 mb-4">Kategori Segera Hadir</h3>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Kami sedang menyiapkan berbagai kategori buku menarik untuk Anda.<br>
                    Pantau terus untuk update terbaru!
                </p>
                <button class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white px-8 py-4 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <span class="flex items-center">
                        <span class="mr-2">🔔</span>
                        Beritahu Saya
                    </span>
                </button>
            </div>
        </div>
        @else
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">
                <span class="text-gray-700">Pilih</span>
                <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Kategori Favorit
                </span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Jelajahi koleksi buku berdasarkan kategori yang sesuai dengan minat dan kebutuhan Anda
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @php
            $categoryIcons = [
            'Fiksi' => '📖',
            'Non-Fiksi' => '📚',
            'Sains' => '🔬',
            'Teknologi' => '💻',
            'Sejarah' => '🏛️',
            'Biografi' => '👤',
            'Kesehatan' => '🏥',
            'Agama' => '🕌',
            'Ekonomi' => '💰',
            'Pendidikan' => '🎓',
            'Seni' => '🎨',
            'Olahraga' => '⚽',
            'Musik' => '🎵',
            'Masakan' => '🍳',
            'Travel' => '✈️',
            'Psikologi' => '🧠',
            ];
            $colors = [
            'from-blue-400 to-purple-500',
            'from-green-400 to-teal-500',
            'from-pink-400 to-red-500',
            'from-yellow-400 to-orange-500',
            'from-indigo-400 to-blue-500',
            'from-purple-400 to-pink-500',
            'from-teal-400 to-green-500',
            'from-orange-400 to-red-500',
            ];
            @endphp

            @foreach($categories as $index => $category)
            <div class="group relative">
                <!-- Glow Effect -->
                <div class="absolute -inset-1 bg-gradient-to-r {{ $colors[$index % count($colors)] }} rounded-2xl blur opacity-0 group-hover:opacity-30 transition-all duration-500"></div>

                <!-- Category Card -->
                <div class="relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group-hover:-translate-y-3 group-hover:rotate-1 border border-gray-100">
                    <!-- Shimmer Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 pointer-events-none"></div>
                    <!-- Category Header -->
                    <div class="relative p-8 bg-gradient-to-br {{ $colors[$index % count($colors)] }} text-white overflow-hidden">
                        <!-- Decorative Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 right-0 w-32 h-32 transform rotate-45 translate-x-16 -translate-y-16">
                                <div class="w-full h-full bg-white rounded-lg"></div>
                            </div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 transform rotate-12 -translate-x-12 translate-y-12">
                                <div class="w-full h-full bg-white rounded-full"></div>
                            </div>
                        </div>

                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 backdrop-blur-sm border border-white/30">
                                {{ $categoryIcons[$category->nama] ?? '📚' }}
                            </div>
                            <h3 class="text-2xl font-bold mb-2 leading-tight group-hover:text-white/90 transition-colors">
                                {{ $category->nama }}
                            </h3>
                            <p class="text-white/80 text-sm font-medium">
                                Eksplorasi Pengetahuan
                            </p>
                        </div>
                    </div>

                    <!-- Category Content -->
                    <div class="p-6">
                        <div class="mb-6">
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                Koleksi buku pilihan dalam kategori {{ $category->nama }} yang telah dikurasi khusus untuk memperkaya wawasan dan pengetahuan Anda.
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-emerald-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Tersedia</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-800">{{ rand(15, 50) }}+</div>
                                    <div class="text-xs text-gray-500">Judul Buku</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('public.categories', $category->id) }}"
                            class="group/btn relative w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r {{ $colors[$index % count($colors)] }} text-white font-semibold rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/25">
                            <span class="absolute inset-0 bg-white/10 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <span class="mr-2">📚</span>
                                Jelajahi Koleksi
                                <svg class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </a>
                    </div>

                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Load More Button (if needed) -->
        <div class="text-center mt-16">
            <button class="group relative inline-flex items-center justify-center px-12 py-4 text-lg font-bold bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-full overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <span class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative flex items-center">
                    <span class="mr-3">🔍</span>
                    Jelajahi Semua Kategori
                    <svg class="ml-3 w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </span>
            </button>
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

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #14b8a6, #10b981);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #0d9488, #059669);
    }
</style>
@endsection