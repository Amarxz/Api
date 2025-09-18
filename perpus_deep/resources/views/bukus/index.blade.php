@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Buku</h2>
    <a href="{{ route('bukus.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Buku Baru</a>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Cover</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Judul</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Penulis</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Kategori</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Penerbit</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Publikasi</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Stok</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bukus as $buku)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">
                        @if ($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="w-16 h-20 object-cover rounded">
                        @else
                        <div class="w-16 h-20 bg-gray-200 flex items-center justify-center text-gray-500 rounded">No Cover</div>
                        @endif
                    </td>
                    <td class="py-2 px-4 font-semibold">{{ $buku->judul }}</td>
                    <td class="py-2 px-4">{{ $buku->penulis }}</td>
                    <td class="py-2 px-4">{{ $buku->kategori->nama ?? 'N/A' }}</td> {{-- Mengakses nama kategori melalui relasi --}}
                    <td class="py-2 px-4">{{ $buku->publisher ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ $buku->tanggal_publikasi ? \Carbon\Carbon::parse($buku->tanggal_publikasi)->format('d M Y') : 'N/A' }}</td>
                    <td class="py-2 px-4">{{ $buku->stok_saat_ini }} / {{ $buku->stok_total }}</td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('bukus.edit', $buku->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('bukus.destroy', $buku->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bukus->links() }} {{-- Tampilkan link pagination --}}
    </div>
</div>
@endsection