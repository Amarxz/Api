@extends('layouts.app')

@section('head')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script>
    tailwind.config = {
        theme: {
            extend: {
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    'fadeInUp': 'fadeInUp 0.8s ease-out',
                    'slideInRight': 'slideInRight 0.6s ease-out',
                }
            }
        }
    }
</script>
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-4 relative overflow-hidden">
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-cyan-300/10 to-blue-300/10 rounded-full blur-3xl animate-float"></div>

        {{-- Floating Particles --}}
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-400/40 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
        <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-purple-400/30 rounded-full animate-bounce" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-indigo-400/50 rounded-full animate-ping" style="animation-delay: 3s;"></div>
    </div>

    {{-- Main Card Container --}}
    <div class="relative w-full max-w-md transform transition-all duration-500 animate-fadeInUp">
        {{-- Glass Morphism Card --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8 relative overflow-hidden group hover:shadow-3xl transition-all duration-500">
            {{-- Card Inner Glow --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            {{-- Header Section --}}
            <div class="text-center mb-8 relative z-10">
                {{-- Animated Icon Container --}}
                <div class="relative inline-flex items-center justify-center w-20 h-20 mb-6">
                    {{-- Background Ring --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl opacity-10 animate-pulse"></div>
                    {{-- Main Icon Container --}}
                    <div class="relative bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl p-4 shadow-lg transform hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-key text-2xl text-white"></i>
                    </div>
                    {{-- Floating Ring Animation --}}
                    <div class="absolute inset-0 border-2 border-blue-400/30 rounded-2xl animate-ping"></div>
                </div>

                {{-- Title and Description --}}
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-3">
                    Reset Password
                </h1>
                <p class="text-gray-600 font-medium leading-relaxed">
                    Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('status'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6 relative overflow-hidden animate-slideInRight">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-green-400 to-emerald-500"></div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-green-800 font-semibold text-sm">Berhasil!</h4>
                        <p class="text-green-700 text-sm mt-1">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6 relative z-10">
                @csrf

                {{-- Email Input --}}
                <div class="space-y-3">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative group">
                        {{-- Input Icon --}}
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-all duration-300"></i>
                        </div>

                        {{-- Input Field --}}
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full pl-12 pr-4 py-4 bg-gray-50/70 border border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white/90
                                   transition-all duration-300 hover:bg-white/50
                                   @error('email') border-red-300 focus:ring-red-500/30 focus:border-red-500 @enderror"
                            placeholder="nama@example.com">

                        {{-- Input Highlight Effect --}}
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-500/0 to-purple-500/0 group-focus-within:from-blue-500/5 group-focus-within:to-purple-500/5 transition-all duration-300 pointer-events-none"></div>
                    </div>

                    {{-- Error Message --}}
                    @error('email')
                    <div class="flex items-center space-x-2 text-red-600 animate-slideInRight">
                        <i class="fas fa-exclamation-circle text-sm"></i>
                        <p class="text-sm font-medium">{{ $message }}</p>
                    </div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-4 px-6 rounded-2xl
                               shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-purple-700 
                               focus:outline-none focus:ring-4 focus:ring-blue-500/30 
                               transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300
                               flex items-center justify-center space-x-3 group relative overflow-hidden">

                        {{-- Button Background Animation --}}
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 to-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>

                        {{-- Button Content --}}
                        <span class="relative z-10">Kirim Link Reset</span>
                        <i class="fas fa-paper-plane relative z-10 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </button>
                </div>

                {{-- Divider --}}
                <div class="relative py-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-4 bg-white/80 text-gray-500 text-sm font-medium">atau</span>
                    </div>
                </div>

                {{-- Back to Login Link --}}
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center space-x-2 text-gray-600 hover:text-blue-600 font-medium transition-all duration-300 group">
                        <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform duration-300"></i>
                        <span>Kembali ke halaman login</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Security Badge --}}
        <div class="mt-8 text-center">
            <div class="inline-flex items-center px-6 py-3 bg-white/70 backdrop-blur-sm rounded-full border border-white/40 shadow-lg hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                        <div class="absolute inset-0 text-green-500 animate-ping opacity-20">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <span class="text-gray-600 font-medium text-sm">Dilindungi dengan enkripsi SSL</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Custom Animations */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
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

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Custom Shadow */
    .shadow-3xl {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced Focus Effects */
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }

    /* Button Glow Effect */
    button[type="submit"]:hover {
        box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.4);
    }

    /* Smooth Transitions */
    * {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

{{-- JavaScript for Enhanced Interactions --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced input focus animations
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add subtle hover effects to the main card
        const card = document.querySelector('.bg-white\\/80');
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });

        // Animate elements on scroll (if needed)
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.animate-fadeInUp').forEach(el => {
            observer.observe(el);
        });
    });

    // Add ripple effect to button
    function createRipple(event) {
        const button = event.currentTarget;
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;

        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
        circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
        circle.classList.add('ripple');

        const ripple = button.getElementsByClassName('ripple')[0];
        if (ripple) {
            ripple.remove();
        }

        button.appendChild(circle);
    }

    // Add ripple effect styles
    const style = document.createElement('style');
    style.textContent = `
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Add ripple to submit button
    document.querySelector('button[type="submit"]').addEventListener('click', createRipple);
</script>
@endsection