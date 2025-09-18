@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Pengguna</h2>
    <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Pengguna Baru</a>

    {{-- Form Pencarian --}}
    <form action="{{ route('users.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan nama, email, atau role..."
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="{{ request('search') }}"> {{-- Menjaga nilai input setelah pencarian --}}
            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('users.index') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">
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
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Nama</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Email</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Telepon</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Role</th>
                    <th class="py-2 px-4 text-left text-gray-600 font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Hitung indeks awal untuk nomor urut
                $page = $users->currentPage() ?: 1;
                $perPage = $users->perPage() ?: 10;
                $i = ($page - 1) * $perPage;
                @endphp
                @forelse ($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4">{{ ++$i }}</td>
                    <td class="py-2 px-4 font-semibold">{{ $user->nama }}</td> {{-- Menggunakan 'nama' --}}
                    <td class="py-2 px-4">{{ $user->email }}</td>
                    <td class="py-2 px-4">{{ $user->telepon ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ ucfirst($user->role) }}</td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{-- Tampilkan link pagination dan sertakan query string pencarian --}}
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection