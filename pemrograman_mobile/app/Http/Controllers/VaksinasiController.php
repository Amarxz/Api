<?php

namespace App\Http\Controllers;

use App\Models\Vaksinasi;
use App\Models\Hewan;
use Illuminate\Http\Request;

class VaksinasiController extends Controller
{
    // Menampilkan daftar vaksinasi
    public function index()
    {
        // Mengambil data vaksinasi
        $vaksinasi = Vaksinasi::all();
        return response()->json($vaksinasi);
    }

    // Menambahkan data vaksinasi baru
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'required|string',
            'nama_vaksin' => 'required|string',
            'jadwal_vaksinasi' => 'required|date',
            'status' => 'required|in:Belum,Selesai',
            'catatan' => 'nullable|string',
        ]);

        // Cari hewan berdasarkan nama_hewan
        $hewan = Hewan::where('nama_hewan', $request->nama_hewan)->first();

        if (!$hewan) {
            return response()->json(['message' => 'Hewan tidak ditemukan'], 404);
        }

        // Menyimpan data vaksinasi
        $vaksinasi = Vaksinasi::create([
            'nama_hewan' => $request->nama_hewan,
            'nama_vaksin' => $request->nama_vaksin,
            'jadwal_vaksinasi' => $request->jadwal_vaksinasi,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return response()->json($vaksinasi, 201);
    }

    // Menampilkan detail vaksinasi berdasarkan ID
    public function show($id)
    {
        // Mencari vaksinasi berdasarkan ID
        $vaksinasi = Vaksinasi::findOrFail($id);
        return response()->json($vaksinasi);
    }

    // Memperbarui data vaksinasi berdasarkan ID
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_vaksin' => 'nullable|string',
            'jadwal_vaksinasi' => 'nullable|date',
            'status' => 'nullable|in:Belum,Selesai',
            'catatan' => 'nullable|string',
        ]);

        // Mencari vaksinasi berdasarkan ID
        $vaksinasi = Vaksinasi::findOrFail($id);

        // Memperbarui data vaksinasi
        $vaksinasi->update($request->only(['nama_vaksin', 'jadwal_vaksinasi', 'status', 'catatan']));

        return response()->json($vaksinasi);
    }

    // Menghapus data vaksinasi berdasarkan ID
    public function destroy($id)
    {
        // Mencari vaksinasi berdasarkan ID
        $vaksinasi = Vaksinasi::findOrFail($id);
        $vaksinasi->delete();

        return response()->json(['message' => 'Data vaksinasi berhasil dihapus']);
    }
}
