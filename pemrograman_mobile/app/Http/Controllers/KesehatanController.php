<?php

namespace App\Http\Controllers;

use App\Models\Kesehatan;
use Illuminate\Http\Request;

class KesehatanController extends Controller
{
    // Menampilkan daftar catatan kesehatan
    public function index()
    {
        // Mengambil semua data kesehatan
        $kesehatan = Kesehatan::all();
        return response()->json($kesehatan);
    }

    // Menambahkan catatan kesehatan baru
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'nama_vaksin' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'gejala' => 'required|string',
            'diagnosis' => 'required|string',
            'tindakan' => 'required|string',
        ]);

        // Menyimpan data catatan kesehatan
        $kesehatan = Kesehatan::create([
            'nama_hewan' => $request->nama_hewan,
            'nama_vaksin' => $request->nama_vaksin,
            'tanggal' => $request->tanggal,
            'gejala' => $request->gejala,
            'diagnosis' => $request->diagnosis,
            'tindakan' => $request->tindakan,
        ]);

        return response()->json($kesehatan, 201);
    }

    // Menampilkan detail catatan kesehatan berdasarkan ID
    public function show($id)
    {
        // Mencari catatan kesehatan berdasarkan ID
        $kesehatan = Kesehatan::findOrFail($id);
        return response()->json($kesehatan);
    }

    // Memperbarui catatan kesehatan berdasarkan ID
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'nullable|string|max:100',
            'nama_vaksin' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'gejala' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        // Mencari catatan kesehatan berdasarkan ID
        $kesehatan = Kesehatan::findOrFail($id);

        // Memperbarui data catatan kesehatan
        $kesehatan->update($request->only(['nama_hewan','nama_vaksin', 'tanggal', 'gejala', 'diagnosis', 'tindakan']));

        return response()->json($kesehatan);
    }

    // Menghapus catatan kesehatan berdasarkan ID
    public function destroy($id)
    {
        // Mencari catatan kesehatan berdasarkan ID
        $kesehatan = Kesehatan::findOrFail($id);
        $kesehatan->delete();

        return response()->json(['message' => 'Catatan kesehatan berhasil dihapus']);
    }
}
