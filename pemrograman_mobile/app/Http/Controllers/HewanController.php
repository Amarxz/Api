<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use Illuminate\Http\Request;

class HewanController extends Controller
{
    // Menampilkan daftar hewan
    public function index()
    {
        // Mengambil semua data hewan
        $hewan = Hewan::all();
        return response()->json($hewan);
    }

    // Menambahkan data hewan baru
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'pemilik' => 'required|string|max:100',
        ]);

        // Menyimpan data hewan
        $hewan = Hewan::create([
            'nama_hewan' => $request->nama_hewan,
            'jenis' => $request->jenis,
            'pemilik' => $request->pemilik,
        ]);

        return response()->json($hewan, 201);
    }

    // Menampilkan detail hewan berdasarkan ID
    public function show($id)
    {
        // Mencari hewan berdasarkan ID
        $hewan = Hewan::findOrFail($id);
        return response()->json($hewan);
    }

    // Memperbarui data hewan berdasarkan ID
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'nullable|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
        ]);

        // Mencari hewan berdasarkan ID
        $hewan = Hewan::findOrFail($id);

        // Memperbarui data hewan
        $hewan->update($request->only(['nama_hewan', 'jenis', 'pemilik']));

        return response()->json($hewan);
    }

    // Menghapus data hewan berdasarkan ID
    public function destroy($id)
    {
        // Mencari hewan berdasarkan ID
        $hewan = Hewan::findOrFail($id);
        $hewan->delete();

        return response()->json(['message' => 'Data hewan berhasil dihapus']);
    }
}
