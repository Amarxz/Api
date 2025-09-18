@extends('layouts.app')

{{-- Menambahkan tautan ke CDN Tailwind CSS --}}
@section('head')
<script src="https://cdn.tailwindcss.com"></script>
{{-- Memastikan font Inter digunakan secara global --}}
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 transform transition-all duration-300 hover:scale-105">
        {{-- Header dengan judul dan ikon --}}
        <div class="text-center mb-8">
            {{-- Ikon untuk Reset Password (misal: Key atau Lock Open) --}}
            <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v5l-3 4H9l-3-4V9a2 2 0 012-2h2m4 0h3m-3 0V4a2 2 0 00-2-2H9a2 2 0 00-2 2v3m-2 7h10a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2z"></path>
            </svg>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Atur Ulang Password Anda
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan password baru Anda di bawah ini
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Token untuk reset password --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Input Email --}}
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        {{-- Ikon Email --}}
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-1 10a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v12z"></path>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                  @error('email') border-red-500 @enderror"
                        placeholder="nama@email.com">

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Input Password Baru --}}
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        {{-- Ikon Gembok --}}
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                  @error('password') border-red-500 @enderror"
                        placeholder="••••••••">

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Input Konfirmasi Password Baru --}}
            <div class="mb-6">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        {{-- Ikon Gembok Konfirmasi --}}
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            {{-- Tombol Reset Password --}}
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-bold text-white bg-blue-600 hover:bg-blue-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out
                               transform hover:scale-100 active:scale-95">
                    Atur Ulang Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection