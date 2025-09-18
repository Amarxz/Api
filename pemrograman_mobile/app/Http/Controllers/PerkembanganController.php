<?php

namespace App\Http\Controllers;

use App\Models\Perkembangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerkembanganController extends Controller
{
    public function index()
    {
        // Mengambil semua data perkembangan
        return Perkembangan::all();
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'required|string',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
            'catatan' => 'required|string',
        ]);

        // Menyimpan foto dan mendapatkan path-nya
        $path = $request->file('foto')->store('perkembangan', 'public');

        // Menyimpan data perkembangan ke database
        $perkembangan = Perkembangan::create([
            'nama_hewan' => $request->nama_hewan,
            'tanggal' => $request->tanggal,
            'berat_badan' => $request->berat_badan,
            'tinggi' => $request->tinggi,
            'foto' => $path,
            'catatan' => $request->catatan,
        ]);

        return response()->json($perkembangan, 201); // Menampilkan data yang berhasil disimpan
    }

    public function show($id)
    {
        // Menampilkan data perkembangan berdasarkan ID
        $perkembangan = Perkembangan::findOrFail($id);
        return response()->json($perkembangan);
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_hewan' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'berat_badan' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'catatan' => 'nullable|string',
        ]);

        // Mencari data perkembangan berdasarkan ID
        $perkembangan = Perkembangan::findOrFail($id);

        // Jika ada foto yang diupload, simpan foto baru dan hapus foto lama
        if ($request->hasFile('foto')) {
            // Menghapus foto lama jika ada
            Storage::disk('public')->delete($perkembangan->foto);

            // Menyimpan foto baru
            $path = $request->file('foto')->store('perkembangan', 'public');
            $perkembangan->foto = $path;
        }

        // Memperbarui data perkembangan
        $perkembangan->update($request->only(['nama_hewan', 'tanggal', 'berat_badan', 'tinggi', 'catatan']));

        return response()->json($perkembangan);
    }

    public function destroy($id)
    {
        // Menghapus data perkembangan berdasarkan ID
        $perkembangan = Perkembangan::findOrFail($id);

        // Menghapus foto dari storage
        Storage::disk('public')->delete($perkembangan->foto);

        // Menghapus data perkembangan dari database
        $perkembangan->delete();

        return response()->json(['message' => 'Data perkembangan berhasil dihapus']);
    }
}
