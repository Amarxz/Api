<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // Import ValidationException

class PeminjamanController extends Controller
{
    public function index()
    {
        return Peminjaman::with('buku', 'user')->get();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'buku_id' => 'required|exists:bukus,id',
                'tanggal_pinjam' => 'required|date',
                'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
                'status' => 'nullable|string|in:dipinjam,dikembalikan,terlambat,dibatalkan', // Tambahkan validasi status
            ]);

            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $validated['buku_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'status' => $validated['status'] ?? 'dipinjam', // Gunakan status dari request, default 'dipinjam'
            ]);

            return response()->json([
                'message' => 'Peminjaman berhasil dibuat',
                'data' => $peminjaman
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with('buku', 'user')->findOrFail($id);
        return response()->json(['data' => $peminjaman]);
    }

    public function update(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            $validated = $request->validate([
                // Pastikan `tanggal_pinjam` dan `buku_id` tidak diubah jika tidak boleh.
                // Jika hanya tanggal_kembali dan status yang bisa diubah, validasi hanya itu.
                'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
                'status' => 'sometimes|required|string|in:dipinjam,dikembalikan,terlambat,dibatalkan', // Tambahkan validasi status untuk update
            ]);

            $peminjaman->update($validated);

            return response()->json([
                'message' => 'Peminjaman berhasil diperbarui',
                'data' => $peminjaman
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Peminjaman::destroy($id);
            return response()->json(['message' => 'Peminjaman berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
