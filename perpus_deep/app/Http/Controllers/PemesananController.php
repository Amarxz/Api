<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan; // Pastikan ini adalah model Pemesanan Anda
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // Import ValidationException

class PemesananController extends Controller
{
    public function index()
    {
        return Pemesanan::with('buku', 'user')->get();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'buku_id' => 'required|exists:bukus,id',
                'tanggal_pesan' => 'required|date',
            ]);

            $pemesanan = Pemesanan::create([
                'user_id' => auth()->id(),
                'buku_id' => $validated['buku_id'],
                'tanggal_pesan' => $validated['tanggal_pesan'],
            ]);

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat',
                'data' => $pemesanan
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
        $pemesanan = Pemesanan::with('buku', 'user')->findOrFail($id);
        return response()->json(['data' => $pemesanan]);
    }

    // PERBAIKAN: Tambahkan metode update()
    public function update(Request $request, $id)
    {
        try {
            $pemesanan = Pemesanan::findOrFail($id);

            // Validasi field yang ingin diupdate
            $validated = $request->validate([
                // Contoh: Jika hanya tanggal_pesan yang bisa diupdate
                'tanggal_pesan' => 'sometimes|required|date',
                // Tambahkan field lain jika API Anda mengizinkan perubahan pada field tersebut
                // 'buku_id' => 'sometimes|required|exists:bukus,id',
                // 'user_id' => 'sometimes|required|exists:users,id',
            ]);

            $pemesanan->update($validated);

            return response()->json([
                'message' => 'Pemesanan berhasil diperbarui',
                'data' => $pemesanan
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
            Pemesanan::destroy($id);
            return response()->json(['message' => 'Pemesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
