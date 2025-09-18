<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; // Import model Buku
use App\Models\Kategori; // Import model Kategori

class PublicController extends Controller
{
    /**
     * Menampilkan halaman beranda portal publik.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil 4 buku terbaru dari database, termasuk relasi kategori
        // Urutkan berdasarkan tanggal publikasi terbaru
        $latestBooks = Buku::with('kategori')
            ->orderBy('tanggal_publikasi', 'desc')
            ->limit(4)
            ->get();

        return view('public.index', compact('latestBooks'));
    }

    /**
     * Menampilkan daftar semua buku di portal publik.
     *
     * @return \Illuminate\View\View
     */
    public function daftarBuku(Request $request)
    {
        // Mengambil semua buku dari database dengan relasi kategori
        // Menambahkan fungsionalitas pencarian dasar
        $query = Buku::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('penulis', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
        }

        // Menggunakan pagination untuk daftar buku
        $books = $query->orderBy('judul', 'asc')->paginate(8); // Tampilkan 8 buku per halaman

        return view('public.books', compact('books'));
    }

    /**
     * Menampilkan detail buku tertentu.
     *
     * @param  int  $id ID Buku
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function detailBuku($id)
    {
        // Mengambil satu buku dari database berdasarkan ID, termasuk relasi kategori
        $book = Buku::with('kategori')->find($id);

        // Menangani jika buku tidak ditemukan
        if (!$book) {
            // Anda bisa mengarahkan kembali ke daftar buku dengan pesan error
            return redirect()->route('public.books')->with('error', 'Buku tidak ditemukan.');
            // Atau menampilkan halaman 404
            // abort(404);
        }

        return view('public.book_detail', compact('book'));
    }

    /**
     * Menampilkan daftar kategori buku.
     *
     * @return \Illuminate\View\View
     */
    public function kategori()
    {
        // Mengambil semua kategori dari database
        $categories = Kategori::orderBy('nama', 'asc')->get();

        return view('public.categories', compact('categories'));
    }

    /**
     * Menampilkan halaman tentang kami.
     *
     * @return \Illuminate\View\View
     */
    public function tentangKami()
    {
        return view('public.about');
    }
}
