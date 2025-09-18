@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Notifikasi</h2>
    <a href="{{ route('notifikasis.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Notifikasi Baru</a>

    {{-- Form Kirim Notifikasi Massal --}}
    <div class="mt-6 mb-4 p-4 bg-gray-50 border rounded-lg">
        <h3 class="text-xl font-bold mb-3">Kirim Notifikasi Massal ke Semua Anggota</h3>
        <form action="{{ route('notifikasis.send_to_all') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="pesan_massal" class="block text-gray-700 text-sm font-bold mb-2">Pesan Untuk Semua Anggota:</label>
                <textarea name="pesan" id="pesan_massal" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('pesan') border-red-500 @enderror">{{ old('pesan') }}</textarea>
                @error('pesan')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Kirim ke Semua Anggota
            </button>
        </form>
    </div>
    {{-- Akhir Form Kirim Notifikasi Massal --}}

    {{-- Form Pencarian --}}
    <form action="{{ route('notifikasis.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan pesan atau pemesan..."
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="{{ request('search') }}"> {{-- Menjaga nilai input setelah pencarian --}}
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('notifikasis.index') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
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
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Penerima</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Pesan</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Status</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Hitung indeks awal untuk nomor urut
                $page = $notifikasis->currentPage() ?: 1;
                $perPage = $notifikasis->perPage() ?: 10;
                $i = ($page - 1) * $perPage;
                @endphp
                @forelse ($notifikasis as $notifikasi)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ ++$i }}</td>
                    <td class="py-2 px-4">{{ $notifikasi->user->nama ?? 'N/A' }}</td> {{-- Menggunakan 'nama' sesuai model User --}}
                    <td class="py-2 px-4">{{ Str::limit($notifikasi->pesan, 50) }}</td> {{-- Batasi panjang pesan untuk tampilan tabel --}}
                    <td class="py-2 px-4">
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            @if($notifikasi->dibaca) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $notifikasi->dibaca ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                        </span>
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('notifikasis.edit', $notifikasi->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('notifikasis.destroy', $notifikasi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada notifikasi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{-- Tampilkan link pagination dan sertakan query string pencarian --}}
        {{ $notifikasis->appends(request()->query())->links() }}
    </div>
</div>
@endsection