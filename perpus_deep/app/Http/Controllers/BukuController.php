<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade untuk manajemen file

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku dengan kategori terkait.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Buku::with('kategori')->get();
    }

    /**
     * Menyimpan buku baru ke database.
     * Mengelola upload cover, validasi input, dan stok awal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Buku
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk semua kolom yang diperlukan
        $validated = $request->validate([
            'judul' => 'required|string|max:255', // Judul buku, wajib diisi
            'penulis' => 'required|string|max:255', // Penulis, wajib diisi
            'kategori_id' => 'required|exists:kategoris,id', // ID kategori, wajib diisi dan harus ada di tabel kategoris
            'stok_total' => 'required|integer|min:0', // Total stok, wajib diisi, bilangan bulat, min 0
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Cover, opsional, harus gambar, format tertentu, maks 2MB
            'publisher' => 'nullable|string|max:255', // Publisher, opsional, string
            'tanggal_publikasi' => 'nullable|date', // Tanggal publikasi, opsional, harus format tanggal
        ]);

        // Stok saat ini diatur sama dengan stok total saat buku pertama kali dibuat
        $validated['stok_saat_ini'] = $validated['stok_total'];

        // Mengelola upload file cover jika ada
        if ($request->hasFile('cover')) {
            // Simpan file ke direktori 'public/covers' dan dapatkan path-nya
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
            // Path yang disimpan akan relatif terhadap storage/app/public,
            // seperti 'covers/namafileunik.jpg'
        }

        // Membuat instance buku baru dengan data yang sudah divalidasi
        $buku = Buku::create($validated);

        // Mengembalikan buku yang baru dibuat
        return $buku;
    }

    /**
     * Menampilkan detail buku tertentu berdasarkan ID.
     * Meload relasi kategori.
     *
     * @param  int  $id
     * @return \App\Models\Buku
     */
    public function show($id)
    {
        // Mencari buku berdasarkan ID dan meload relasi kategori, jika tidak ditemukan akan menghasilkan 404
        return Buku::with('kategori')->findOrFail($id);
    }

    /**
     * Memperbarui data buku yang sudah ada.
     * Mengelola upload cover baru dan penghapusan cover lama.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\Buku
     */
    public function update(Request $request, $id)
    {
        // Mencari buku yang akan diperbarui, jika tidak ditemukan akan menghasilkan 404
        $buku = Buku::findOrFail($id);

        // Aturan validasi untuk kolom yang bisa diperbarui
        // 'sometimes' digunakan agar kolom hanya divalidasi jika ada dalam request
        $validated = $request->validate([
            'judul' => 'sometimes|string|max:255',
            'penulis' => 'sometimes|string|max:255',
            'kategori_id' => 'sometimes|exists:kategoris,id',
            'stok_total' => 'sometimes|integer|min:0',
            'stok_saat_ini' => 'sometimes|integer|min:0|lte:stok_total', // Stok saat ini tidak boleh lebih dari stok total
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Cover, opsional, harus gambar, format tertentu, maks 2MB
            'publisher' => 'nullable|string|max:255',
            'tanggal_publikasi' => 'nullable|date',
        ]);

        // Mengelola upload file cover baru jika ada
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            // Simpan cover baru
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        } elseif (array_key_exists('cover', $request->all()) && $request->input('cover') === null) {
            // Jika input cover dikirim dengan nilai null (misalnya, untuk menghapus cover yang sudah ada)
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = null; // Setel cover ke null di database
        }


        // Memperbarui buku dengan data yang sudah divalidasi
        $buku->update($validated);

        // Mengembalikan buku yang sudah diperbarui
        return $buku;
    }

    /**
     * Menghapus buku dari database.
     * Juga menghapus file cover yang terkait.
     *
     * @param  int  $id
     * @return int
     */
    public function destroy($id)
    {
        // Mencari buku yang akan dihapus
        $buku = Buku::findOrFail($id);

        // Hapus file cover terkait dari storage jika ada
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        // Hapus buku dari database
        return $buku->delete();
    }
}
