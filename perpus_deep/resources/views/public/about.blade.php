@extends('layouts.public')

@section('title', 'Tentang Kami - Portal Informasi Perpustakaan')

@section('content')
<!-- Hero Header Section -->
<section class="relative bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 py-20 overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-40 h-40 bg-teal-200/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-52 h-52 bg-emerald-200/30 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-cyan-200/30 rounded-full blur-2xl"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-12 right-1/4 text-teal-300/40 text-5xl animate-float">🏛️</div>
        <div class="absolute bottom-12 left-1/4 text-emerald-300/40 text-4xl animate-float-delayed">📚</div>
        <div class="absolute top-1/3 right-1/3 text-cyan-300/40 text-3xl animate-float-slow">✨</div>
        <div class="absolute bottom-1/3 left-1/3 text-teal-300/40 text-3xl animate-float">🌟</div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-gray-800 mb-6 leading-tight">
                <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Tentang
                </span>
                <span class="text-gray-700">Perpustakaan</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 leading-relaxed">
                Mengenal lebih dekat visi, misi, dan komitmen kami dalam memajukan literasi digital
            </p>
        </div>
    </div>
</section>

<div class="container mx-auto px-6 py-16">
    {{-- Introduction Section with Enhanced Design --}}
    <div class="relative mb-20">
        <!-- Glow Effect -->
        <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-3xl blur opacity-0 hover:opacity-20 transition-all duration-500"></div>

        <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-10 md:p-12 lg:p-16 border border-gray-200/50 group hover:shadow-3xl transition-all duration-500">
            <!-- Icon Header -->
            <div class="text-center mb-10">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-4xl shadow-xl group-hover:scale-110 transition-transform duration-300">
                    🏛️
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Perpustakaan Digital</h2>
            </div>

            <div class="max-w-4xl mx-auto space-y-8">
                <p class="text-lg md:text-xl text-gray-700 leading-relaxed text-center">
                    Perpustakaan kami didirikan dengan visi untuk menjadi <strong class="text-teal-600">pusat pengetahuan dan inspirasi</strong>
                    bagi seluruh komunitas. Kami berdedikasi untuk menyediakan akses mudah ke berbagai sumber daya literasi dan informasi
                    yang relevan, mendukung pembelajaran seumur hidup, dan mempromosikan budaya membaca di era digital.
                </p>

                <div class="relative">
                    <div class="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-teal-400 to-emerald-500 rounded-full"></div>
                    <p class="text-lg md:text-xl text-gray-700 leading-relaxed pl-8">
                        Sejak didirikan pada tahun <strong class="text-emerald-600">2020</strong>, kami terus berkembang, memperkaya koleksi digital kami,
                        dan meningkatkan layanan untuk memenuhi kebutuhan pembaca modern. Kami percaya bahwa setiap buku memiliki cerita yang unik,
                        dan setiap cerita memiliki kekuatan untuk <strong class="text-teal-600">mengubah hidup</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Vision & Mission Section --}}
    <div class="mb-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">
                <span class="text-gray-700">Visi &</span>
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Misi</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Fondasi yang menguatkan setiap langkah perjalanan kami dalam memajukan literasi
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
            <!-- Vision Card -->
            <div class="group relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-emerald-400 rounded-3xl blur opacity-0 group-hover:opacity-25 transition-all duration-500"></div>
                <div class="relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl p-8 md:p-10 border border-gray-200/50 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-8 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🎯
                    </div>

                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">Visi Kami</h3>

                    <div class="relative">
                        <div class="absolute left-4 top-0 w-0.5 h-full bg-gradient-to-b from-teal-400 to-emerald-500 rounded-full"></div>
                        <p class="text-gray-700 text-lg leading-relaxed pl-12">
                            Menjadi <strong class="text-teal-600">pusat literasi digital terdepan</strong> yang memberdayakan
                            individu melalui akses tak terbatas terhadap pengetahuan dan inspirasi, serta menciptakan
                            komunitas pembelajar yang aktif dan berkelanjutan.
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="mt-8 pt-6 border-t border-gray-200/50">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-teal-600">∞</div>
                                <div class="text-sm text-gray-600">Akses Tak Terbatas</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-emerald-600">🌍</div>
                                <div class="text-sm text-gray-600">Jangkauan Global</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission Card -->
            <div class="group relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-purple-400 to-pink-400 rounded-3xl blur opacity-0 group-hover:opacity-25 transition-all duration-500"></div>
                <div class="relative bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl p-8 md:p-10 border border-gray-200/50 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-8 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🚀
                    </div>

                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">Misi Kami</h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-3 flex-shrink-0"></div>
                            <p class="text-gray-700 leading-relaxed">
                                <strong class="text-purple-600">Menyediakan koleksi digital</strong> yang beragam dan mutakhir
                            </p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-2 h-2 bg-pink-500 rounded-full mt-3 flex-shrink-0"></div>
                            <p class="text-gray-700 leading-relaxed">
                                <strong class="text-pink-600">Menciptakan lingkungan belajar</strong> yang nyaman dan inspiratif
                            </p>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-3 flex-shrink-0"></div>
                            <p class="text-gray-700 leading-relaxed">
                                <strong class="text-purple-600">Menyelenggarakan program literasi</strong> yang inovatif dan berkelanjutan
                            </p>
                        </div>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="mt-8 pt-6 border-t border-gray-200/50">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress Misi 2024</span>
                            <span>85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-400 to-pink-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Our Values Section --}}
    <div class="mb-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">
                <span class="bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">Nilai-Nilai</span>
                <span class="text-gray-700">Kami</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Prinsip yang memandu setiap tindakan dan keputusan dalam melayani komunitas
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Value 1 -->
            <div class="group text-center">
                <div class="relative mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🎓
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pembelajaran</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Komitmen untuk terus belajar dan berkembang bersama komunitas
                </p>
            </div>

            <!-- Value 2 -->
            <div class="group text-center">
                <div class="relative mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🤝
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Kolaborasi</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Membangun kemitraan yang kuat dengan berbagai pihak
                </p>
            </div>

            <!-- Value 3 -->
            <div class="group text-center">
                <div class="relative mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        🔬
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Inovasi</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Mengadopsi teknologi terdepan untuk pengalaman terbaik
                </p>
            </div>

            <!-- Value 4 -->
            <div class="group text-center">
                <div class="relative mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-pink-400 to-rose-500 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        ❤️
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Integritas</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Menjaga kepercayaan dengan transparansi dan kejujuran
                </p>
            </div>
        </div>
    </div>

    {{-- Contact Information Section --}}
    <div class="relative">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-emerald-50 rounded-3xl"></div>

        <div class="relative bg-white/60 backdrop-blur-sm rounded-3xl shadow-2xl p-10 md:p-12 lg:p-16 border border-gray-200/50">
            <div class="text-center mb-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-4xl shadow-xl">
                    📬
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kami siap membantu dan mendengar masukan Anda untuk pelayanan yang lebih baik
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- Address -->
                    <div class="group flex items-start space-x-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center text-white text-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            📍
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Alamat Lengkap</h3>
                            <p class="text-gray-700 leading-relaxed">
                                Jalan Perpustakaan Indah No. 123<br>
                                Kota Pengetahuan, Kode Pos 12345<br>
                                Indonesia
                            </p>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="group flex items-start space-x-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center text-white text-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            📞
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Kontak</h3>
                            <p class="text-gray-700 mb-1">
                                <strong>Email:</strong> info@perpusku.com
                            </p>
                            <p class="text-gray-700">
                                <strong>Telepon:</strong> (021) 12345678
                            </p>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="group flex items-start space-x-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center text-white text-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            ⏰
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Jam Operasional</h3>
                            <div class="text-gray-700 space-y-1">
                                <p><strong>Senin - Jumat:</strong> 08:00 - 20:00</p>
                                <p><strong>Sabtu - Minggu:</strong> 09:00 - 17:00</p>
                                <p class="text-teal-600 font-semibold">🌐 Akses Digital: 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/80 rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Aksi Cepat</h3>

                    <div class="space-y-4">
                        <!-- Email Button -->
                        <a href="mailto:info@perpusku.com"
                            class="group w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <span class="mr-3 text-xl">📧</span>
                            <span>Kirim Email</span>
                            <svg class="ml-3 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <!-- Books Button -->
                        <a href="{{ route('public.books') }}"
                            class="group w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <span class="mr-3 text-xl">📚</span>
                            <span>Jelajahi Koleksi</span>
                            <svg class="ml-3 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <!-- Social Media Links -->
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-center text-gray-600 mb-4">Ikuti Kami</p>
                            <div class="flex justify-center space-x-4">
                                <a href="#" class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300">
                                    📘
                                </a>
                                <a href="#" class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300">
                                    📷
                                </a>
                                <a href="#" class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-300">
                                    🐦
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    /* Custom Shadow */
    .shadow-3xl {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
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