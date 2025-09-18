@extends('layouts.public')

@section('title')
{{ isset($book) && $book ? $book->judul : 'Detail Buku Tidak Ditemukan' }} - Portal Informasi Perpustakaan
@endsection

@section('content')
<div class="container mx-auto my-12 p-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        {{-- Pesan Error/Notifikasi --}}
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if (!isset($book) || !$book)
        {{-- Tampilan jika buku tidak ditemukan --}}
        <div class="text-center py-10">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-4">Buku Tidak Ditemukan</h2>
            <p class="text-gray-600 mb-8">Maaf, buku yang Anda cari tidak dapat ditemukan. Mungkin ID buku tidak valid atau buku telah dihapus.</p>
            <a href="{{ route('public.books') }}"
                class="bg-teal-600 text-white px-6 py-3 rounded-full text-lg font-semibold hover:bg-teal-700 transition duration-300 transform hover:scale-105 shadow-lg">
                Kembali ke Daftar Buku
            </a>
        </div>
        @else
        {{-- Tampilan Detail Buku --}}
        <div class="flex flex-col md:flex-row gap-8">
            {{-- Bagian Gambar Sampul --}}
            <div class="md:w-1/3 flex-shrink-0">
                <img src="{{ $book->cover ?: 'https://placehold.co/600x800/e0e0e0/555555?text=Sampul+Tidak+Tersedia' }}"
                    alt="Sampul Buku {{ $book->judul }}"
                    class="w-full h-auto rounded-lg shadow-xl object-cover"
                    onerror="this.onerror=null;this.src='https://placehold.co/600x800/e0e0e0/555555?text=Sampul+Tidak+Tersedia';">
            </div>

            {{-- Bagian Informasi Buku --}}
            <div class="md:w-2/3">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $book->judul }}</h1>
                <p class="text-xl text-gray-700 mb-6">Oleh: <span class="font-semibold">{{ $book->penulis }}</span></p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-gray-700">
                    <div>
                        <strong class="block text-gray-800">Kategori:</strong>
                        <span class="text-teal-600 font-medium">{{ $book->kategori->nama ?? 'Tidak Diketahui' }}</span>
                    </div>
                    <div>
                        <strong class="block text-gray-800">Penerbit:</strong>
                        <span>{{ $book->publisher ?? '-' }}</span>
                    </div>
                    <div>
                        <strong class="block text-gray-800">Tahun Publikasi:</strong>
                        <span>{{ \Carbon\Carbon::parse($book->tanggal_publikasi)->format('Y') ?? '-' }}</span>
                    </div>
                    <div>
                        <strong class="block text-gray-800">ISBN:</strong>
                        <span>{{ $book->isbn ?? '-' }}</span> {{-- Asumsi ada kolom ISBN di model Buku --}}
                    </div>
                    <div>
                        <strong class="block text-gray-800">Jumlah Halaman:</strong>
                        <span>{{ $book->pages ?? '-' }}</span> {{-- Asumsi ada kolom pages di model Buku --}}
                    </div>
                    <div>
                        <strong class="block text-gray-800">Bahasa:</strong>
                        <span>{{ $book->language ?? '-' }}</span> {{-- Asumsi ada kolom language di model Buku --}}
                    </div>
                    <div>
                        <strong class="block text-gray-800">Stok Tersedia:</strong>
                        <span class="font-bold text-lg {{ $book->stok_saat_ini > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $book->stok_saat_ini }}
                        </span> / {{ $book->stok_total }}
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-3">Sinopsis / Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed mb-8">
                    {{ $book->description ?? 'Tidak ada deskripsi yang tersedia untuk buku ini.' }}
                </p>

                {{-- Tombol Kembali --}}
                <a href="{{ route('public.books') }}"
                    class="inline-flex items-center bg-gray-200 text-gray-700 px-6 py-3 rounded-full text-lg font-semibold hover:bg-gray-300 transition duration-300 transform hover:scale-105 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Buku
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection