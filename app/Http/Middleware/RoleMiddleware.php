<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani permintaan masuk dan memeriksa hak akses (RBAC).
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah terautentikasi
        if (! auth()->check()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
        }

        // 2. Ambil role user, bersihkan spasi, dan ubah ke huruf kecil semua
        $userRole = strtolower(trim(auth()->user()->role ?? 'nasabah'));

        // 3. Bersihkan parameter roles dan ubah semuanya ke huruf kecil
        $cleanedRoles = array_map(function ($role) {
            return strtolower(trim($role));
        }, $roles);

        // Jika middleware memeriksa 'nasabah', izinkan juga kata kunci 'user' dan 'warga'
        if (in_array('nasabah', $cleanedRoles)) {
            $cleanedRoles[] = 'user';
            $cleanedRoles[] = 'warga';
        }

        // 4. Cek kecocokan hak akses (bebas kapitalisasi)
        if (! in_array($userRole, $cleanedRoles)) {
            // Jika role tidak cocok, jangan abort/loop, melainkan lempar ke dashboard yang sesuai
            if (in_array($userRole, ['admin', 'petugas'])) {
                return redirect()->route('dashboard.staf');
            }

            return redirect()->route('dashboard.user');
        }

        return $next($request);
    }
}