@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Notifikasi #{{ $notifikasi->id }}</h2>
    <form action="{{ route('notifikasis.update', $notifikasi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Penerima (Anggota):</label>
            <select name="user_id" id="user_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror">
                <option value="">Pilih Penerima</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $notifikasi->user_id) == $user->id ? 'selected' : '' }}>
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
            <textarea name="pesan" id="pesan" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('pesan') border-red-500 @enderror">{{ old('pesan', $notifikasi->pesan) }}</textarea>
            @error('pesan')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="dibaca" class="block text-gray-700 text-sm font-bold mb-2">Status Dibaca:</label>
            <input type="checkbox" name="dibaca" id="dibaca" class="form-checkbox h-5 w-5 text-blue-600" value="1" {{ old('dibaca', $notifikasi->dibaca) ? 'checked' : '' }}>
            <span class="ml-2 text-gray-700">Sudah Dibaca</span>
            @error('dibaca')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Notifikasi
            </button>
            <a href="{{ route('notifikasis.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection