@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Tambah Pemesanan Baru</h2>
    <form action="{{ route('pemesanan.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Pemesan (Anggota):</label>
            <select name="user_id" id="user_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror">
                <option value="">Pilih Anggota</option>
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
            <label for="buku_id" class="block text-gray-700 text-sm font-bold mb-2">Buku:</label>
            <select name="buku_id" id="buku_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('buku_id') border-red-500 @enderror">
                <option value="">Pilih Buku</option>
                @foreach ($bukus as $buku)
                <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                    {{ $buku->judul }} (Stok: {{ $buku->stok_saat_ini }})
                </option>
                @endforeach
            </select>
            @error('buku_id')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tanggal_pesan" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pesan:</label>
            <input type="date" name="tanggal_pesan" id="tanggal_pesan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_pesan') border-red-500 @enderror" value="{{ old('tanggal_pesan', \Carbon\Carbon::now()->format('Y-m-d')) }}">
            @error('tanggal_pesan')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Jika Anda ingin menambahkan status pemesanan, tambahkan dropdown di sini --}}
        {{--
        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status Pemesanan:</label>
            <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="siap_ambil" {{ old('status') == 'siap_ambil' ? 'selected' : '' }}>Siap Ambil</option>
        <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        @error('status')
        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
        @enderror
</div>
--}}

<div class="flex items-center justify-between">
    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Simpan Pemesanan
    </button>
    <a href="{{ route('pemesanan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Batal
    </a>
</div>
</form>
</div>
@endsection