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

    {{-- Login Container --}}
    <div class="relative w-full max-w-md">
        {{-- Main Card --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 transform transition-all duration-500 hover:shadow-3xl">
            {{-- Header --}}
            <div class="text-center mb-8">
                {{-- Logo/Icon --}}
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl mb-6 shadow-lg">
                    <i class="fas fa-user-shield text-2xl text-white"></i>
                </div>

                {{-- Welcome Text --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Selamat Datang
                </h1>
                <p class="text-gray-600 font-medium">
                    Masuk ke akun Anda untuk melanjutkan
                </p>
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Email Field --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Alamat Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white
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

                {{-- Password Field --}}
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        Kata Sandi
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full pl-12 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white
                                   transition-all duration-300 @error('password') border-red-400 focus:ring-red-500/30 focus:border-red-500 @enderror"
                            placeholder="••••••••••">

                        {{-- Show/Hide Password Toggle --}}
                        <button
                            type="button"
                            onclick="togglePassword()"
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

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer group">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors duration-200">
                            Ingat saya
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-all duration-200">
                        Lupa password?
                    </a>
                    @endif
                </div>

                {{-- Login Button --}}
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-4 px-6 rounded-2xl
                           shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-purple-700 
                           focus:outline-none focus:ring-4 focus:ring-blue-500/30 
                           transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300
                           flex items-center justify-center space-x-2 group">
                    <span>Masuk ke Akun</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </button>

                {{-- Divider --}}
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">atau</span>
                    </div>
                </div>

                {{-- Social Login Buttons (Optional) --}}
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

            {{-- Register Link --}}
            @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}"
                        class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-all duration-200 ml-1">
                        Daftar sekarang
                    </a>
                </p>
            </div>
            @endif
        </div>

        {{-- Security Badge --}}
        <div class="mt-6 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-sm rounded-full border border-white/30 shadow-sm">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                <span class="text-sm text-gray-600 font-medium">Dilindungi SSL 256-bit</span>
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
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Checkbox custom styles */
    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    /* Button hover glow effect */
    button[type="submit"]:hover {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }
</style>

{{-- JavaScript --}}
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('password-toggle-icon');

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
</script>
@endsection