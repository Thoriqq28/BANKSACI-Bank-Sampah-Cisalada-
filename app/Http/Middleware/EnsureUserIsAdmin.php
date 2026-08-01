<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Silakan login terlebih dahulu.']);
        }

        // 2. Cek apakah role-nya benar-benar admin
        if (Auth::user()->role !== 'admin') {
            // Jika bukan admin, tendang ke dashboard nasabah atau kasih error 403
            abort(403, 'Akses Ditolak! Halaman ini hanya untuk Administrator.');
        }

        return $next($request);
    }
}