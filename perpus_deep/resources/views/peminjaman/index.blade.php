@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Peminjaman</h2>
    <a href="{{ route('peminjaman.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Peminjaman Baru</a>

    {{-- Form Pencarian --}}
    <form action="{{ route('peminjaman.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan nama peminjam, judul buku, atau status..."
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('peminjaman.index') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
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
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Peminjam</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Buku</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Tgl Pinjam</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Tgl Kembali</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Status</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                $page = $peminjamans->currentPage() ?: 1;
                $perPage = $peminjamans->perPage() ?: 10;
                $i = ($page - 1) * $perPage;
                @endphp
                @forelse ($peminjamans as $peminjaman)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ ++$i }}</td>
                    <td class="py-2 px-4">{{ $peminjaman->user->nama ?? 'N/A' }}</td> {{-- <-- UBAH DARI 'user->name' MENJADI 'user->nama' --}}
                    <td class="py-2 px-4">{{ $peminjaman->buku->judul ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            @if($peminjaman->status == 'dipinjam') bg-blue-100 text-blue-800
                            @elseif($peminjaman->status == 'dikembalikan') bg-green-100 text-green-800
                            @elseif($peminjaman->status == 'terlambat') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">Tidak ada peminjaman ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $peminjamans->appends(request()->query())->links() }}
    </div>
</div>
@endsection