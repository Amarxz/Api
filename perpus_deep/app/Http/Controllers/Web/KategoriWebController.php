<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriWebController extends Controller
{
    /**
     * Tampilkan daftar sumber daya.
     */
    public function __construct()
    {
        // Hanya admin yang bisa mengakses semua metode di controller ini,
        // kecuali mungkin 'show' jika diizinkan untuk user itu sendiri.
        // Untuk CRUD admin, kita asumsikan semua diakses admin.
        $this->middleware('admin'); // Kita akan membuat middleware 'admin' jika belum ada
    }

    public function index(Request $request) // <-- Tambahkan parameter Request $request
    {
        $query = Kategori::orderBy('id', 'asc'); // Mulai dengan query dasar dan urutan ascending

        // Jika ada input 'search' dari form
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', '%' . $search . '%'); // Tambahkan kondisi WHERE untuk pencarian
        }

        // Terapkan paginasi pada query yang sudah difilter
        $kategoris = $query->paginate(10); // Ambil kategori yang difilter dan paginasi

        return view('kategoris.index', compact('kategoris'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        return view('kategoris.create');
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(Kategori $kategori)
    {
        return view('kategoris.show', compact('kategori'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategoris.edit', compact('kategori'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
        ]);

        $kategori->update($validated);

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();
            return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('kategoris.index')->with('error', 'Tidak dapat menghapus kategori karena masih ada buku yang terkait!');
        } catch (\Exception $e) {
            return redirect()->route('kategoris.index')->with('error', 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage());
        }
    }
}
