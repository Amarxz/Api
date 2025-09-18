@extends('layouts.app')

@section('head')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-4 relative overflow-hidden">
    {{-- Background Decorations --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-cyan-300/10 to-blue-300/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Register Container --}}
    <div class="relative w-full max-w-lg">
        {{-- Main Card --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 transform transition-all duration-500 hover:shadow-3xl">
            {{-- Header --}}
            <div class="text-center mb-8">
                {{-- Logo/Icon --}}
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-600 to-blue-600 rounded-2xl mb-6 shadow-lg">
                    <i class="fas fa-user-plus text-2xl text-white"></i>
                </div>

                {{-- Welcome Text --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Bergabung Dengan Kami
                </h1>
                <p class="text-gray-600 font-medium">
                    Buat akun baru untuk memulai perjalanan Anda
                </p>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name Field --}}
                <div class="space-y-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700">
                        Nama Lengkap
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-user text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                        </div>
                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            required
                            autocomplete="nama"
                            autofocus
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 focus:bg-white
                                   transition-all duration-300 @error('nama') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap Anda">
                    </div>
                    @error('nama')
                    <p class="text-sm text-red-600 font-medium flex items-center mt-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Alamat Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 focus:bg-white
                                   transition-all duration-300 @error('email') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                    <p class="text-sm text-red-600 font-medium flex items-center mt-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Phone Field --}}
                <div class="space-y-2">
                    <label for="telepon" class="block text-sm font-semibold text-gray-700">
                        Nomor Telepon <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-phone text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                        </div>
                        <input
                            id="telepon"
                            type="tel"
                            name="telepon"
                            value="{{ old('telepon') }}"
                            autocomplete="tel"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 focus:bg-white
                                   transition-all duration-300 @error('telepon') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror"
                            placeholder="08123456789">
                    </div>
                    @error('telepon')
                    <p class="text-sm text-red-600 font-medium flex items-center mt-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Password Fields Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Password Field --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Kata Sandi
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full pl-12 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                       focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 focus:bg-white
                                       transition-all duration-300 @error('password') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror"
                                placeholder="••••••••••">

                            {{-- Show/Hide Password Toggle --}}
                            <button
                                type="button"
                                onclick="togglePassword('password', 'password-toggle-icon')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i id="password-toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-sm text-red-600 font-medium flex items-center mt-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Confirm Password Field --}}
                    <div class="space-y-2">
                        <label for="password-confirm" class="block text-sm font-semibold text-gray-700">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i class="fas fa-lock text-gray-400 group-focus-within:text-green-500 transition-colors duration-200"></i>
                            </div>
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full pl-12 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                       focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 focus:bg-white
                                       transition-all duration-300"
                                placeholder="••••••••••">

                            {{-- Show/Hide Password Toggle --}}
                            <button
                                type="button"
                                onclick="togglePassword('password-confirm', 'password-confirm-toggle-icon')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i id="password-confirm-toggle-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                <div class="flex items-start space-x-3 pt-2">
                    <input
                        id="terms"
                        name="terms"
                        type="checkbox"
                        required
                        class="w-5 h-5 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 transition-all duration-200 mt-0.5">
                    <label for="terms" class="text-sm text-gray-600 leading-relaxed">
                        Saya menyetujui
                        <a href="#" class="text-green-600 hover:text-green-700 hover:underline font-medium transition-colors duration-200">Syarat & Ketentuan</a>
                        dan
                        <a href="#" class="text-green-600 hover:text-green-700 hover:underline font-medium transition-colors duration-200">Kebijakan Privasi</a>
                    </label>
                </div>

                {{-- Register Button --}}
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white font-bold py-4 px-6 rounded-2xl
                           shadow-lg hover:shadow-xl hover:from-green-700 hover:to-blue-700 
                           focus:outline-none focus:ring-4 focus:ring-green-500/30 
                           transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300
                           flex items-center justify-center space-x-2 group mt-6">
                    <span>Buat Akun Sekarang</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </button>

                {{-- Divider --}}
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">atau daftar dengan</span>
                    </div>
                </div>

                {{-- Social Register Buttons --}}
                <div class="grid grid-cols-2 gap-3">
                    <button
                        type="button"
                        class="flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500/30 transition-all duration-200">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </button>
                    <button
                        type="button"
                        class="flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500/30 transition-all duration-200">
                        <i class="fab fa-microsoft text-blue-600 mr-2"></i>
                        Microsoft
                    </button>
                </div>
            </form>

            {{-- Login Link --}}
            @if (Route::has('login'))
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}"
                        class="font-semibold text-green-600 hover:text-green-700 hover:underline transition-all duration-200 ml-1">
                        Masuk di sini
                    </a>
                </p>
            </div>
            @endif
        </div>

        {{-- Security Badge --}}
        <div class="mt-6 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-sm rounded-full border border-white/30 shadow-sm">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600 font-medium">Data Anda Aman & Terenkripsi</span>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Custom shadow */
    .shadow-3xl {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
    }

    /* Smooth animations */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom focus styles */
    input:focus {
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Checkbox custom styles */
    input[type="checkbox"]:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    /* Button hover glow effect */
    button[type="submit"]:hover {
        box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
    }

    /* Password strength indicator */
    .password-strength {
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }

    .password-strength-bar {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
</style>

{{-- JavaScript --}}
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }

    // Add subtle animations on load
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.bg-white\\/80');
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);

        // Password matching validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password-confirm');

        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Kata sandi tidak cocok');
                confirmPassword.classList.add('border-red-400');
            } else {
                confirmPassword.setCustomValidity('');
                confirmPassword.classList.remove('border-red-400');
            }
        });
    });

    // Add input focus animations
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-[1.02]');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-[1.02]');
        });
    });

    // Form validation enhancement
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password-confirm').value;
        const terms = document.getElementById('terms').checked;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Kata sandi dan konfirmasi kata sandi tidak cocok!');
            return false;
        }

        if (!terms) {
            e.preventDefault();
            alert('Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan!');
            return false;
        }
    });
</script>
@endsection