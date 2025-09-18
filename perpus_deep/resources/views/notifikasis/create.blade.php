@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Tambah Notifikasi Baru</h2>
    <form action="{{ route('notifikasis.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Penerima (Anggota):</label>
            <select name="user_id" id="user_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror">
                <option value="">Pilih Penerima</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->nama }} {{-- Menggunakan 'nama' sesuai model User --}}
                </option>
                @endforeach
            </select>
            @error('user_id')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="pesan" class="block text-gray-700 text-sm font-bold mb-2">Pesan Notifikasi:</label>
            <textarea name="pesan" id="pesan" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('pesan') border-red-500 @enderror">{{ old('pesan') }}</textarea>
            @error('pesan')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kolom 'dibaca' tidak perlu di form create, karena akan otomatis false --}}

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Simpan Notifikasi
            </button>
            <a href="{{ route('notifikasis.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection