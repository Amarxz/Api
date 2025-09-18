@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Kategori</h2>
    <a href="{{ route('kategoris.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Kategori Baru</a>

    {{-- Form Pencarian --}}
    <form action="{{ route('kategoris.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan nama kategori..."
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ request('search') }}"> {{-- Menjaga nilai input setelah pencarian --}}
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('kategoris.index') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">No.</th> {{-- Ubah ID menjadi No. --}}
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Nama Kategori</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Hitung indeks awal untuk nomor urut, mempertahankan query string untuk paginasi
                    $page = $kategoris->currentPage() ?: 1;
                    $perPage = $kategoris->perPage() ?: 10; // Default ke 10 jika perPage tidak tersedia
                    $i = ($page - 1) * $perPage;
                @endphp
                @forelse ($kategoris as $kategori)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ ++$i }}</td> {{-- Tampilkan nomor urut --}}
                    <td class="py-2 px-4 font-semibold">{{ $kategori->nama }}</td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('kategoris.edit', $kategori->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Menghapus kategori juga akan menghapus semua buku yang terkait dengannya.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 px-4 text-center text-gray-500">Tidak ada kategori ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{-- Tampilkan link pagination dan sertakan query string pencarian --}}
        {{ $kategoris->appends(request()->query())->links() }}
    </div>
</div>
@endsection
