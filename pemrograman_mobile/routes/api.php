<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HewanController;

use App\Http\Controllers\VaksinasiController;
use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\PerkembanganController;
use App\Http\Controllers\ObatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(Authenticate::using('sanctum'));

Route::middleware('api')->group(function () {
    Route::post('/hewan', [HewanController::class, 'store']);
});



// Rute untuk Hewan
Route::get('/hewan', [HewanController::class, 'index']); // Menampilkan semua hewan
Route::post('/hewan', [HewanController::class, 'store']); // Menambahkan hewan baru
Route::get('/hewan/{id}', [HewanController::class, 'show']); // Menampilkan hewan berdasarkan ID
Route::put('/hewan/{id}', [HewanController::class, 'update']); // Memperbarui hewan berdasarkan ID
Route::delete('/hewan/{id}', [HewanController::class, 'destroy']); // Menghapus hewan berdasarkan ID

// Rute untuk Vaksinasi
Route::get('/vaksinasi', [VaksinasiController::class, 'index']); // Menampilkan semua vaksinasi
Route::post('/vaksinasi', [VaksinasiController::class, 'store']); // Menambahkan vaksinasi baru
Route::get('/vaksinasi/{id}', [VaksinasiController::class, 'show']); // Menampilkan vaksinasi berdasarkan ID
Route::put('/vaksinasi/{id}', [VaksinasiController::class, 'update']); // Memperbarui vaksinasi berdasarkan ID
Route::delete('/vaksinasi/{id}', [VaksinasiController::class, 'destroy']); // Menghapus vaksinasi berdasarkan ID

// Rute untuk Kesehatan
Route::get('/kesehatan', [KesehatanController::class, 'index']); // Menampilkan semua catatan kesehatan
Route::post('/kesehatan', [KesehatanController::class, 'store']); // Menambahkan catatan kesehatan baru
Route::get('/kesehatan/{id}', [KesehatanController::class, 'show']); // Menampilkan catatan kesehatan berdasarkan ID
Route::put('/kesehatan/{id}', [KesehatanController::class, 'update']); // Memperbarui catatan kesehatan berdasarkan ID
Route::delete('/kesehatan/{id}', [KesehatanController::class, 'destroy']); // Menghapus catatan kesehatan berdasarkan ID

// Rute untuk Perkembangan
Route::get('/perkembangan', [PerkembanganController::class, 'index']); // Menampilkan semua data perkembangan
Route::post('/perkembangan', [PerkembanganController::class, 'store']); // Menambahkan data perkembangan baru
Route::get('/perkembangan/{id}', [PerkembanganController::class, 'show']); // Menampilkan perkembangan berdasarkan ID
Route::put('/perkembangan/{id}', [PerkembanganController::class, 'update']); // Memperbarui perkembangan berdasarkan ID
Route::delete('/perkembangan/{id}', [PerkembanganController::class, 'destroy']); // Menghapus perkembangan berdasarkan ID

// Rute untuk Obat
Route::get('/obat', [ObatController::class, 'index']); // Menampilkan semua obat
Route::post('/obat', [ObatController::class, 'store']); // Menambahkan obat baru
Route::get('/obat/{id}', [ObatController::class, 'show']); // Menampilkan obat berdasarkan ID
Route::put('/obat/{id}', [ObatController::class, 'update']); // Memperbarui obat berdasarkan ID
Route::delete('/obat/{id}', [ObatController::class, 'destroy']); // Menghapus obat berdasarkan ID