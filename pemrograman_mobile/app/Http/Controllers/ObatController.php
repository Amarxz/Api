<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        // Menampilkan semua data obat
        return Obat::all();
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_obat' => 'required|string',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        // Menyimpan data obat baru
        $obat = Obat::create([
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        return response()->json($obat, 201); // Menampilkan data yang berhasil disimpan
    }

    public function show($id)
    {
        // Menampilkan data obat berdasarkan ID
        $obat = Obat::findOrFail($id);
        return response()->json($obat);
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_obat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer',
            'harga' => 'nullable|numeric',
        ]);

        // Mencari data obat berdasarkan ID
        $obat = Obat::findOrFail($id);

        // Memperbarui data obat
        $obat->update($request->only(['nama_obat', 'deskripsi', 'stok', 'harga']));

        return response()->json($obat);
    }

    public function destroy($id)
    {
        // Menghapus data obat berdasarkan ID
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return response()->json(['message' => 'Obat berhasil dihapus']);
    }
}
