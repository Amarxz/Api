<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\BukuWebController;
use App\Http\Controllers\Web\KategoriWebController;
use App\Http\Controllers\Web\UserWebController;
use App\Http\Controllers\Web\PeminjamanWebController;
use App\Http\Controllers\Web\PemesananWebController;
use App\Http\Controllers\Web\NotifikasiWebController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Web\AuthWebController; // PENTING: Perubahan di sini!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Web Routes (Custom)
Route::controller(AuthWebController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');

    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');

    Route::post('/logout', 'logout')->name('logout');

    // Password Reset Routes
    Route::get('/password/reset', 'showForgotPasswordForm')->name('password.request');
    Route::post('/password/email', 'sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'showResetPasswordForm')->name('password.reset');
    Route::post('/password/reset', 'resetPassword')->name('password.update');

    // Password Confirmation
    Route::get('/password/confirm', 'showConfirmForm')->name('password.confirm');
    Route::post('/password/confirm', 'confirmPassword');
});

// Redirect from /home to /dashboard for authenticated users
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');

// Admin Portal Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    // Redirect root URL to dashboard if authenticated
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bukus', BukuWebController::class);
    Route::resource('kategoris', KategoriWebController::class);
    Route::resource('users', UserWebController::class);
    Route::resource('peminjaman', PeminjamanWebController::class);
    Route::resource('pemesanan', PemesananWebController::class);

    Route::post('notifikasis/send-to-all', [NotifikasiWebController::class, 'sendToAll'])->name('notifikasis.send_to_all');
    Route::resource('notifikasis', NotifikasiWebController::class);
});

// Public Information Portal Routes (Accessible to all)
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'index')->name('public.home');
    Route::get('/buku', 'daftarBuku')->name('public.books');
    Route::get('/buku/{id}', 'detailBuku')->name('public.book.detail');
    Route::get('/kategori', 'kategori')->name('public.categories');
    Route::get('/tentang-kami', 'tentangKami')->name('public.about');
});
