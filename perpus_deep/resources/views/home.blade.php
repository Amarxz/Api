@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    {{-- Hero Section --}}
    <div class="relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" class="text-indigo-600" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
            @if (session('status'))
            <div class="mb-8 max-w-md mx-auto">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-6 py-4 shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Main Hero Content --}}
            <div class="text-center">
                {{-- Welcome Badge --}}
                <div class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium mb-8 animate-fade-in-up">
                    <i class="fas fa-sparkles mr-2"></i>
                    Selamat Datang Kembali!
                </div>

                {{-- Main Title --}}
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 animate-fade-in-up animation-delay-200">
                    Dashboard
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent block mt-2">
                        Anda Sudah Masuk
                    </span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed animate-fade-in-up animation-delay-400">
                    Selamat datang di sistem kami! Anda telah berhasil login dan siap untuk memulai perjalanan digital yang menakjubkan.
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16 animate-fade-in-up animation-delay-600">
                    <a href="#features" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-rocket mr-2"></i>
                        Mulai Jelajahi
                    </a>
                    <a href="#about" class="inline-flex items-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up animation-delay-800">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="text-3xl text-indigo-600 mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">1,234</div>
                        <div class="text-sm text-gray-600">Pengguna Aktif</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="text-3xl text-emerald-600 mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">98.5%</div>
                        <div class="text-sm text-gray-600">Tingkat Kepuasan</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="text-3xl text-purple-600 mb-3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">24/7</div>
                        <div class="text-sm text-gray-600">Dukungan Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Features Section --}}
    <div id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Temukan berbagai fitur canggih yang dirancang khusus untuk kemudahan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="group bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-indigo-100">
                    <div class="w-14 h-14 bg-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Performa Tinggi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem yang dioptimalkan untuk memberikan pengalaman pengguna yang cepat dan responsif.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="group bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-emerald-100">
                    <div class="w-14 h-14 bg-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dilindungi dengan teknologi enkripsi terdepan untuk menjaga data Anda tetap aman.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="group bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-purple-100">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-magic text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mudah Digunakan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Interface yang intuitif dan user-friendly untuk semua kalangan pengguna.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="group bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-amber-100">
                    <div class="w-14 h-14 bg-amber-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Responsif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dapat diakses dengan sempurna di berbagai perangkat, mulai dari desktop hingga mobile.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="group bg-gradient-to-br from-rose-50 to-red-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-rose-100">
                    <div class="w-14 h-14 bg-rose-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-headset text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Dukungan 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tim support yang siap membantu Anda kapan saja dengan respon yang cepat dan profesional.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="group bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 border border-cyan-100">
                    <div class="w-14 h-14 bg-cyan-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-sync-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Update Berkala</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Fitur dan keamanan yang terus diperbarui untuk memberikan pengalaman terbaik.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- About Section --}}
    <div id="about" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Tentang Platform Kami
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Kami berkomitmen untuk memberikan pengalaman digital terbaik dengan teknologi terdepan dan desain yang mengutamakan kemudahan pengguna.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700">Interface yang modern dan intuitif</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700">Performa optimal di semua perangkat</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-gray-700">Keamanan dan privasi terjamin</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-2xl">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-award text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Penghargaan Terbaru</h3>
                            <p class="text-indigo-100 mb-6">
                                Platform terbaik tahun ini untuk kategori inovasi dan kemudahan penggunaan.
                            </p>
                            <div class="flex justify-center space-x-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Siap Memulai Perjalanan Anda?
            </h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan pengguna yang telah merasakan pengalaman luar biasa bersama platform kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-play mr-2"></i>
                    Mulai Sekarang
                </a>
                <a href="#" class="inline-flex items-center px-8 py-4 bg-transparent text-white font-semibold rounded-xl border border-white border-opacity-30 hover:bg-white hover:bg-opacity-10 transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .animation-delay-200 {
        animation-delay: 0.2s;
    }

    .animation-delay-400 {
        animation-delay: 0.4s;
    }

    .animation-delay-600 {
        animation-delay: 0.6s;
    }

    .animation-delay-800 {
        animation-delay: 0.8s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
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
        background: linear-gradient(to bottom, #6366f1, #a855f7);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #4f46e5, #9333ea);
    }

    /* Hover effects */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
</style>
@endsection