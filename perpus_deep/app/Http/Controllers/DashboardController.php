<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; // Import model Buku Anda yang ada
use App\Models\Peminjaman; // Import model Peminjaman Anda yang ada
use App\Models\User; // Import model User Anda yang ada

class DashboardController extends Controller
{
    /**
     * Buat instance controller baru.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya pengguna terautentikasi yang bisa mengakses dashboard ini
    }

    /**
     * Tampilkan dashboard aplikasi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Anda bisa mengambil data statistik dari database Anda melalui model
        $totalBuku = Buku::count();
        // Asumsi 'status' di model Peminjaman adalah 'dipinjam' untuk yang aktif
        $totalPeminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        // Asumsi Anda memiliki kolom 'role' di tabel users dan 'anggota' adalah role untuk anggota
        $totalAnggota = User::where('role', 'anggota')->count();

        // Mengirim data ke view dashboard
        return view('dashboard', compact('totalBuku', 'totalPeminjamanAktif', 'totalAnggota'));
    }
}
