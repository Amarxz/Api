<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException; // Import ValidationException

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Hapus token lama untuk mencegah token berlebihan jika tidak diinginkan
        // $user->tokens()->delete(); 

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil', // Tambahkan pesan konfirmasi
            'user' => $user,
            'token' => $token
        ], 200); // Pastikan status code 200 untuk berhasil
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'telepon' => 'nullable|string|max:15',
                'role' => 'nullable|in:admin,anggota' // 'nullable' karena ada default di bawah
            ]);

            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telepon' => $validated['telepon'] ?? null,
                'role' => $validated['role'] ?? 'anggota', // default jika tidak diisi
            ]);

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'message' => 'Registrasi berhasil',
                'user' => $user, // Sertakan data user dan token setelah registrasi berhasil
                'token' => $token
            ], 201); // Status 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422); // Status 422 Unprocessable Entity untuk validasi
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUsers() // Metode ini digunakan untuk mengambil semua user (misal untuk admin)
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'admin') { // Perbaikan: Tambahkan ! $user check
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::all();

        return response()->json([
            'message' => 'List user', // Tambahkan pesan konfirmasi
            'data' => $users
        ], 200);
    }
}
