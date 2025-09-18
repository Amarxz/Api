@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Pemesanan</h2>
    <a href="{{ route('pemesanan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Pemesanan Baru</a>

    {{-- Form Pencarian --}}
    <form action="{{ route('pemesanan.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan nama pemesan atau judul buku..."
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="{{ request('search') }}"> {{-- Menjaga nilai input setelah pencarian --}}
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('pemesanan.index') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Reset
            </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">No.</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Pemesan</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Buku</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Tgl Pesan</th>
                    {{-- Tambahkan kolom status jika Anda ingin melacak status pemesanan --}}
                    {{-- <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Status</th> --}}
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Hitung indeks awal untuk nomor urut
                $page = $pemesanans->currentPage() ?: 1;
                $perPage = $pemesanans->perPage() ?: 10;
                $i = ($page - 1) * $perPage;
                @endphp
                @forelse ($pemesanans as $pemesanan)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ ++$i }}</td>
                    <td class="py-2 px-4">{{ $pemesanan->user->nama ?? 'N/A' }}</td> {{-- Menggunakan 'nama' sesuai model User --}}
                    <td class="py-2 px-4">{{ $pemesanan->buku->judul ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pesan)->format('d M Y') }}</td>
                    {{-- Tampilkan status jika ada --}}
                    {{-- <td class="py-2 px-4">{{ ucfirst($pemesanan->status ?? 'N/A') }}</td> --}}
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('pemesanan.edit', $pemesanan->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('pemesanan.destroy', $pemesanan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada pemesanan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{-- Tampilkan link pagination dan sertakan query string pencarian --}}
        {{ $pemesanans->appends(request()->query())->links() }}
    </div>
</div>
@endsection