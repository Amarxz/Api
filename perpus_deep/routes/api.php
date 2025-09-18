<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController; // Pastikan ini diimpor jika Anda menggunakan UserController


// Route untuk mengambil user yang sedang login (dilindungi autentikasi)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});

// Route public (tidak butuh autentikasi)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route yang butuh autentikasi (dilindungi oleh Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // PERBAIKAN: Gunakan apiResource untuk user, yang akan menangani GET, POST, PUT, DELETE
    // Ini mengasumsikan Anda memiliki App\Http\Controllers\UserController
    // Hapus baris: Route::get('/users', [AuthController::class, 'getAllUsers']);
    Route::apiResource('users', UserController::class);

    // Resource routes lainnya
    Route::apiResource('kategoris', KategoriController::class);
    Route::apiResource('bukus', BukuController::class);
    Route::apiResource('pemesanans', PemesananController::class);
    Route::apiResource('peminjamans', PeminjamanController::class);
    Route::apiResource('notifikasis', NotifikasiController::class);
});
