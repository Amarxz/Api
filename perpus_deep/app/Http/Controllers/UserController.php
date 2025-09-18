<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     * Hanya bisa diakses oleh admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Memastikan hanya user dengan role 'admin' yang bisa mengakses
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::all(); // Ambil semua data user
        return response()->json([
            'message' => 'List user berhasil dimuat',
            'data' => $users
        ], 200);
    }

    /**
     * Menyimpan user baru.
     * Dapat digunakan sebagai alternatif untuk endpoint register jika ingin membuat user baru oleh admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input untuk membuat user baru
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users', // Email harus unik
                'password' => 'required|string|min:6',
                'telepon' => 'nullable|string|max:15',
                'role' => 'nullable|in:admin,anggota' // Role bisa diatur, default 'anggota'
            ]);

            // Buat user baru
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // Hash password
                'telepon' => $validated['telepon'] ?? null,
                'role' => $validated['role'] ?? 'anggota', // Default role 'anggota'
            ]);

            return response()->json([
                'message' => 'User berhasil ditambahkan',
                'data' => $user
            ], 201); // Status 201 Created

        } catch (ValidationException $e) {
            // Tangani error validasi
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422); // Status 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Tangani error umum server
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail user tertentu.
     * Hanya bisa diakses oleh admin atau user itu sendiri.
     *
     * @param  \App\Models\User  $user  (menggunakan Route Model Binding)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        // Memastikan hanya admin atau user itu sendiri yang bisa melihat detail
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Detail user',
            'data' => $user
        ], 200);
    }

    /**
     * Memperbarui user tertentu.
     * Hanya bisa diakses oleh admin atau user itu sendiri.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user  (menggunakan Route Model Binding)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        // Memastikan hanya admin atau user itu sendiri yang bisa memperbarui
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            // Validasi input untuk update user
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Email harus unik kecuali untuk user ini sendiri
                'telepon' => 'nullable|string|max:15',
                'role' => 'sometimes|in:admin,anggota' // 'sometimes' agar tidak wajib dikirim jika tidak diubah
            ]);

            // Update data user
            $user->update($validated);

            // Update password jika ada password baru yang dikirim
            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:6']); // Validasi password baru
                $user->password = Hash::make($request->password); // Hash dan simpan password baru
                $user->save();
            }

            return response()->json([
                'message' => 'User berhasil diperbarui',
                'data' => $user
            ], 200);
        } catch (ValidationException $e) {
            // Tangani error validasi
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Tangani error umum server
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus user tertentu.
     * Hanya bisa diakses oleh admin.
     *
     * @param  \App\Models\User  $user  (menggunakan Route Model Binding)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Memastikan hanya admin yang bisa menghapus user
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $user->delete(); // Hapus user
            return response()->json(['message' => 'User berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
