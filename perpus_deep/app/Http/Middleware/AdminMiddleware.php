<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Penting: Import Auth facade

class AdminMiddleware
{
    /**
     * Tangani permintaan masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna sudah login
        if (Auth::check()) {
            // Periksa apakah peran (role) pengguna adalah 'admin'
            if (Auth::user()->role === 'admin') {
                return $next($request); // Jika admin, lanjutkan permintaan
            } else {
                // Jika bukan admin, redirect ke halaman utama atau berikan error
                return redirect('/home')->with('error', 'Anda tidak memiliki izin akses sebagai Admin.');
                // Atau, jika Anda ingin langsung 403 Forbidden:
                // abort(403, 'Unauthorized. You do not have admin access.');
            }
        }

        // Jika pengguna belum login, arahkan ke halaman login
        return redirect('/login')->with('error', 'Silakan login untuk mengakses halaman ini.');
    }
}
