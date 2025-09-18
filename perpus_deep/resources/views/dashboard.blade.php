@extends('layouts.app')

@section('content')
{{-- Main Container dengan desain yang lebih soft dan modern --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-6">
    {{-- Header Section --}}
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-book-reader text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 tracking-tight">
                PerpusDeep
                <span class="block text-2xl md:text-3xl font-medium text-blue-600 mt-2">Admin & User Dashboard</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Kelola perpustakaan digital Anda dengan mudah dan efisien
            </p>
        </div>

        {{-- Statistics Cards dengan desain card yang lebih clean --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            {{-- Total Buku Card --}}
            <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300 cursor-pointer">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center justify-center w-14 h-14 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors duration-300">
                        <i class="fas fa-book text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Buku</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBuku }}</p>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full w-4/5 group-hover:w-full transition-all duration-500"></div>
                </div>
            </div>

            {{-- Peminjaman Aktif Card --}}
            <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-300 cursor-pointer">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center justify-center w-14 h-14 bg-emerald-50 rounded-xl group-hover:bg-emerald-100 transition-colors duration-300">
                        <i class="fas fa-hand-holding-heart text-2xl text-emerald-600"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Peminjaman Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPeminjamanAktif }}</p>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full w-3/5 group-hover:w-full transition-all duration-500"></div>
                </div>
            </div>

            {{-- Anggota Terdaftar Card --}}
            <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-amber-200 transition-all duration-300 cursor-pointer">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center justify-center w-14 h-14 bg-amber-50 rounded-xl group-hover:bg-amber-100 transition-colors duration-300">
                        <i class="fas fa-users text-2xl text-amber-600"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Anggota Terdaftar</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAnggota }}</p>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-full w-2/3 group-hover:w-full transition-all duration-500"></div>
                </div>
            </div>
        </div>

        {{-- Quick Access Section dengan grid yang lebih responsive --}}
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Akses Cepat</h2>
                <p class="text-gray-600">Navigasi cepat ke fitur utama sistem</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Manajemen Buku --}}
                <a href="{{ route('bukus.index') }}" class="group block">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 text-center hover:from-blue-100 hover:to-blue-200 transition-all duration-300 border border-blue-200">
                        <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full mx-auto mb-4 shadow-sm group-hover:shadow-md transition-shadow duration-300">
                            <i class="fas fa-book-open text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Manajemen Buku</h3>
                        <p class="text-sm text-gray-600">Kelola koleksi buku perpustakaan</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center text-blue-600 text-sm font-medium group-hover:text-blue-700">
                                Akses Sekarang
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Kelola Peminjaman --}}
                <a href="{{ route('peminjaman.index') }}" class="group block">
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 text-center hover:from-emerald-100 hover:to-emerald-200 transition-all duration-300 border border-emerald-200">
                        <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full mx-auto mb-4 shadow-sm group-hover:shadow-md transition-shadow duration-300">
                            <i class="fas fa-exchange-alt text-2xl text-emerald-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Peminjaman</h3>
                        <p class="text-sm text-gray-600">Pantau dan kelola peminjaman buku</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center text-emerald-600 text-sm font-medium group-hover:text-emerald-700">
                                Akses Sekarang
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Kelola Pengguna --}}
                <a href="{{ route('users.index') }}" class="group block">
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-6 text-center hover:from-amber-100 hover:to-amber-200 transition-all duration-300 border border-amber-200">
                        <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full mx-auto mb-4 shadow-sm group-hover:shadow-md transition-shadow duration-300">
                            <i class="fas fa-user-friends text-2xl text-amber-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Pengguna</h3>
                        <p class="text-sm text-gray-600">Manajemen anggota perpustakaan</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center text-amber-600 text-sm font-medium group-hover:text-amber-700">
                                Akses Sekarang
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Activity Summary Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            {{-- Recent Activity Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                        <i class="fas fa-clock text-sm text-gray-600"></i>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">Buku baru ditambahkan</p>
                            <p class="text-xs text-gray-500">2 jam yang lalu</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-emerald-50 rounded-lg">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">Peminjaman baru</p>
                            <p class="text-xs text-gray-500">5 jam yang lalu</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-amber-50 rounded-lg">
                        <div class="w-2 h-2 bg-amber-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">Anggota baru terdaftar</p>
                            <p class="text-xs text-gray-500">1 hari yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Status Card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Status Sistem</h3>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-green-600 font-medium">Online</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Database</span>
                        <span class="text-sm font-medium text-green-600">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Server</span>
                        <span class="text-sm font-medium text-green-600">Normal</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Backup</span>
                        <span class="text-sm font-medium text-blue-600">Terjadwal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS untuk animasi yang lebih halus --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection