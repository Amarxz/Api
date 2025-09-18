@extends('layouts.app')

{{-- Menambahkan tautan ke CDN Tailwind CSS. Pastikan ini dimuat di layout utama Anda. --}}
@section('head')
<script src="https://cdn.tailwindcss.com"></script>
{{-- Memastikan font Inter digunakan secara global untuk tampilan yang konsisten --}}
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
        {{-- Header dengan judul dan ikon yang relevan --}}
        <div class="text-center mb-8">
            {{-- Ikon untuk Konfirmasi Password (misal: Lock Closed) --}}
            <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Konfirmasi Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Silakan konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            {{-- Input Password dengan ikon --}}
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        {{-- Ikon Gembok --}}
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                                  @error('password') border-red-500 @enderror"
                        placeholder="••••••••">

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Konfirmasi Password --}}
            <div class="mb-4">
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-bold text-white bg-blue-600 hover:bg-blue-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out
                               transform hover:scale-100 active:scale-95">
                    Konfirmasi Password
                </button>
            </div>

            {{-- Link Lupa Password --}}
            <div class="text-center">
                @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-500 hover:underline transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                    Lupa Password Anda?
                </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection