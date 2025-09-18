@extends('layouts.public')

@section('title', 'Daftar Buku - Portal Informasi Perpustakaan')

@section('content')
<!-- Hero Header Section -->
<section class="relative bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 py-16 overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-teal-200/30 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-emerald-200/30 rounded-full blur-2xl"></div>
        <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-cyan-200/30 rounded-full blur-xl"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-8 right-1/4 text-teal-300/40 text-4xl animate-float">📚</div>
        <div class="absolute bottom-8 left-1/4 text-emerald-300/40 text-3xl animate-float-delayed">📖</div>
        <div class="absolute top-1/3 right-1/3 text-cyan-300/40 text-2xl animate-float-slow">✨</div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-black text-gray-800 mb-4">
                <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Perpustakaan
                </span>
                <span class="text-gray-700">Digital</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Jelajahi koleksi lengkap buku-buku pilihan dari berbagai kategori dan genre
            </p>
        </div>
    </div>
</section>

<div class="container mx-auto px-6 py-12">
    <!-- Advanced Search Section -->
    <div class="mb-12">
        <div class="max-w-4xl mx-auto">
            <form action="{{ route('public.books') }}" method="GET" class="relative">
                <!-- Main Search Bar -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-2xl blur opacity-0 group-hover:opacity-20 transition-all duration-500"></div>
                    <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="flex items-center">
                            <!-- Search Icon -->
                            <div class="pl-6 pr-4 py-5">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <!-- Search Input -->
                            <input type="text"
                                name="search"
                                placeholder="Cari berdasarkan judul, penulis, atau kategori..."
                                class="flex-1 py-5 px-4 text-lg text-gray-700 placeholder-gray-400 bg-transparent border-0 focus:outline-none focus:ring-0"
                                value="{{ request('search') }}">

                            <!-- Search Button -->
                            <button type="submit"
                                class="group/btn relative px-8 py-5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold transition-all duration-300 hover:from-emerald-500 hover:to-teal-500">
                                <span class="flex items-center">
                                    <span class="mr-2">🔍</span>
                                    <span class="hidden sm:inline">Cari Buku</span>
                                    <span class="sm:hidden">Cari</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Suggestions -->
                @if(request('search'))
                <div class="mt-4 text-center">
                    <p class="text-gray-600">
                        Menampilkan hasil untuk:
                        <span class="font-semibold text-teal-600">"{{ request('search') }}"</span>
                        <a href="{{ route('public.books') }}" class="ml-2 text-sm text-gray-500 hover:text-gray-700 underline">
                            Hapus Filter
                        </a>
                    </p>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Notification Messages -->
    @if (session('error'))
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 rounded-r-xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-2xl">❌</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-red-800">Terjadi Kesalahan</h3>
                    <p class="text-red-600">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-r-xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-green-800">Berhasil!</h3>
                    <p class="text-green-600">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Books Grid Section -->
    @if ($books->isEmpty())
    <!-- Empty State -->
    <div class="text-center py-20">
        <div class="max-w-lg mx-auto">
            <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center text-6xl">
                🔍
            </div>
            <h3 class="text-3xl font-bold text-gray-700 mb-4">Buku Tidak Ditemukan</h3>
            <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                @if(request('search'))
                Pencarian untuk <strong>"{{ request('search') }}"</strong> tidak menghasilkan buku apapun.
                <br>Coba gunakan kata kunci yang berbeda.
                @else
                Saat ini belum ada buku yang tersedia di perpustakaan.
                @endif
            </p>
            <div class="space-y-4">
                @if(request('search'))
                <a href="{{ route('public.books') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-full hover:shadow-lg transition-all duration-300">
                    <span class="mr-2">📚</span>
                    Lihat Semua Buku
                </a>
                @endif
                <div>
                    <button onclick="document.querySelector('input[name=search]').focus()"
                        class="text-teal-600 hover:text-emerald-600 font-medium transition-colors">
                        Coba Pencarian Lain
                    </button>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Results Header -->
    <div class="mb-8 text-center">
        <p class="text-lg text-gray-600">
            Menampilkan
            <span class="font-semibold text-teal-600">{{ $books->count() }}</span>
            dari
            <span class="font-semibold text-emerald-600">{{ $books->total() }}</span>
            buku
            @if(request('search'))
            untuk "<span class="font-semibold">{{ request('search') }}</span>"
            @endif
        </p>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
        @foreach($books as $book)
        <div class="group relative">
            <!-- Glow Effect -->
            <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-2xl blur opacity-0 group-hover:opacity-25 transition-all duration-500"></div>

            <!-- Book Card -->
            <div class="relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group-hover:-translate-y-2">
                <!-- Book Cover -->
                <div class="relative overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                    <img src="{{ $book->cover ?: 'https://placehold.co/400x300/e2e8f0/64748b?text=📚+Sampul+Tidak+Tersedia' }}"
                        alt="Sampul {{ $book->judul }}"
                        class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700"
                        onerror="this.onerror=null;this.src='https://placehold.co/400x300/e2e8f0/64748b?text=📚+Sampul+Tidak+Tersedia';">

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Category Badge -->
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-teal-600 px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                        {{ $book->kategori->nama ?? 'Umum' }}
                    </div>
                </div>

                <!-- Book Info -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-teal-600 transition-colors leading-tight">
                        {{ $book->judul }}
                    </h3>

                    <!-- Author -->
                    <div class="flex items-center mb-2">
                        <span class="text-sm text-gray-500 mr-2">✍️</span>
                        <p class="text-emerald-600 font-semibold text-sm">{{ $book->penulis }}</p>
                    </div>

                    <!-- Category -->
                    <div class="flex items-center mb-6">
                        <span class="text-sm text-gray-500 mr-2">🏷️</span>
                        <p class="text-gray-600 text-sm">
                            <span class="text-teal-600 font-medium">{{ $book->kategori->nama ?? 'Tidak Diketahui' }}</span>
                        </p>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('public.book.detail', $book->id) }}"
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

    <!-- Enhanced Pagination -->
    <div class="flex justify-center">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            {{ $books->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

<!-- Custom Animations & Styles -->
<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(3deg);
        }
    }

    @keyframes float-delayed {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-12px) rotate(-2deg);
        }
    }

    @keyframes float-slow {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-8px) rotate(1deg);
        }
    }

    .animate-float {
        animation: float 4s ease-in-out infinite;
    }

    .animate-float-delayed {
        animation: float-delayed 6s ease-in-out infinite 1s;
    }

    .animate-float-slow {
        animation: float-slow 8s ease-in-out infinite 0.5s;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-clamp: 2;
        overflow: hidden;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
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