<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Buku; // Menggunakan model Buku Anda yang sudah ada
use App\Models\Kategori; // Menggunakan model Kategori Anda yang sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk manajemen file cover

class BukuWebController extends Controller
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

    public function index()
    {
        // Ambil semua buku dengan relasi kategori, diurutkan berdasarkan ID terbaru, dan paginasi
        // Model Buku dan Kategori akan diakses langsung dari proyek yang sama.
        $bukus = Buku::with('kategori')->orderBy('id', 'desc')->paginate(10);
        return view('bukus.index', compact('bukus'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        // Ambil semua kategori untuk dropdown di form
        $kategoris = Kategori::all();
        return view('bukus.create', compact('kategoris'));
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id', // Kategori harus ada di tabel kategoris
            'stok_total' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
            'publisher' => 'nullable|string|max:255',
            'tanggal_publikasi' => 'nullable|date', // Validasi format tanggal
        ]);

        // Stok saat ini diatur sama dengan stok total saat buku baru dibuat
        $validated['stok_saat_ini'] = $validated['stok_total'];

        // Jika ada file cover yang diupload, simpan ke storage
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Buat entri buku baru di database melalui model
        Buku::create($validated);

        // Redirect kembali ke daftar buku dengan pesan sukses
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(Buku $buku)
    {
        // Tampilkan detail buku (opsional, karena biasanya CRUD pakai index/edit saja)
        return view('bukus.show', compact('buku'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Buku $buku)
    {
        // Ambil semua kategori untuk dropdown di form edit
        $kategoris = Kategori::all();
        return view('bukus.edit', compact('buku', 'kategoris'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, Buku $buku)
    {
        // Validasi data yang masuk. Gunakan 'sometimes' agar kolom hanya divalidasi jika ada di request.
        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'penulis' => 'sometimes|required|string|max:255',
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
            'stok_total' => 'sometimes|required|integer|min:0',
            'stok_saat_ini' => 'sometimes|required|integer|min:0|lte:stok_total', // Stok saat ini tidak boleh lebih dari total
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'publisher' => 'nullable|string|max:255',
            'tanggal_publikasi' => 'nullable|date',
        ]);

        // Logika untuk mengelola file cover saat update
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            // Simpan cover baru
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        } elseif (array_key_exists('cover', $request->all()) && $request->input('cover') === null) {
            // Jika input 'cover' secara eksplisit disetel null (misalnya, untuk menghapus cover)
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = null; // Setel kolom cover di database menjadi null
        }

        // Perbarui data buku melalui model
        $buku->update($validated);

        // Redirect kembali ke daftar buku dengan pesan sukses
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Buku $buku)
    {
        // Hapus file cover terkait dari storage jika ada
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }
        // Hapus buku dari database melalui model
        $buku->delete();

        // Redirect kembali ke daftar buku dengan pesan sukses
        return redirect()->route('bukus.index')->with('success', 'Buku berhasil dihapus!');
    }
}
