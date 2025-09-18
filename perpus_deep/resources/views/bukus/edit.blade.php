@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Buku: {{ $buku->judul }}</h2>
    <form action="{{ route('bukus.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting: Gunakan metode PUT untuk update --}}

        <div class="mb-4">
            <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul Buku:</label>
            <input type="text" name="judul" id="judul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('judul') border-red-500 @enderror" value="{{ old('judul', $buku->judul) }}">
            @error('judul')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="penulis" class="block text-gray-700 text-sm font-bold mb-2">Penulis:</label>
            <input type="text" name="penulis" id="penulis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('penulis') border-red-500 @enderror" value="{{ old('penulis', $buku->penulis) }}">
            @error('penulis')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="publisher" class="block text-gray-700 text-sm font-bold mb-2">Penerbit:</label>
            <input type="text" name="publisher" id="publisher" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('publisher') border-red-500 @enderror" value="{{ old('publisher', $buku->publisher) }}">
            @error('publisher')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tanggal_publikasi" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Publikasi:</label>
            <input type="date" name="tanggal_publikasi" id="tanggal_publikasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_publikasi') border-red-500 @enderror" value="{{ old('tanggal_publikasi', $buku->tanggal_publikasi) }}">
            @error('tanggal_publikasi')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
            <select name="kategori_id" id="kategori_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kategori_id') border-red-500 @enderror">
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
                @endforeach
            </select>
            @error('kategori_id')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="stok_total" class="block text-gray-700 text-sm font-bold mb-2">Stok Total:</label>
            <input type="number" name="stok_total" id="stok_total" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stok_total') border-red-500 @enderror" value="{{ old('stok_total', $buku->stok_total) }}">
            @error('stok_total')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="stok_saat_ini" class="block text-gray-700 text-sm font-bold mb-2">Stok Saat Ini:</label>
            <input type="number" name="stok_saat_ini" id="stok_saat_ini" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stok_saat_ini') border-red-500 @enderror" value="{{ old('stok_saat_ini', $buku->stok_saat_ini) }}">
            @error('stok_saat_ini')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="cover" class="block text-gray-700 text-sm font-bold mb-2">Cover Buku:</label>
            @if ($buku->cover)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $buku->cover) }}" alt="Current Cover" class="w-32 h-40 object-cover rounded">
                <p class="text-gray-600 text-sm mt-1">Cover saat ini.</p>
            </div>
            @endif
            <input type="file" name="cover" id="cover" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cover') border-red-500 @enderror">
            <p class="text-gray-600 text-sm mt-1">Biarkan kosong jika tidak ingin mengubah cover.</p>
            @error('cover')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Buku
            </button>
            <a href="{{ route('bukus.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection